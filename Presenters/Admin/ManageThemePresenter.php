<?php

require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Admin/ImageUploadDirectory.php');

use LibreBooking\Common\Security\SvgSanitizer;

class ManageThemePresenter extends ActionPresenter
{
    /**
     * @var ManageThemePage
     */
    private $page;

    public function __construct(ManageThemePage $page)
    {
        parent::__construct($page);
        $this->page = $page;
        $this->AddAction('update', 'UpdateTheme');
        $this->AddAction('removeLogo', 'RemoveLogo');
        $this->AddAction('removeFavicon', 'RemoveFavicon');
        $this->AddAction('removeCss', 'RemoveCss');
    }

    public function UpdateTheme()
    {
        $logoFile = $this->page->GetLogoFile();
        $cssFile = $this->page->GetCssFile();
        $favicon = $this->page->GetFaviconFile();
        // Read before removing anything, so a rejected upload keeps the current image
        $logoImage = $logoFile == null ? null : $this->ReadImage(file: $logoFile);
        $faviconImage = $favicon == null ? null : $this->ReadImage(file: $favicon);

        if ($logoImage !== null) {
            Log::Debug('Replacing logo with ' . $logoFile->OriginalName());

            $this->StoreImage(
                baseName: 'custom-logo',
                extension: $logoFile->Extension(),
                contents: $logoImage,
                removeOld: fn () => $this->RemoveLogo()
            );
        }
        if ($cssFile != null) {
            Log::Debug('Replacing css file with ' . $cssFile->OriginalName());
            $target = ROOT_DIR . 'Web/css/custom-style.css';
            $copied = copy($cssFile->TemporaryName(), $target);
            if (!$copied) {
                Log::Error(
                    'Could not replace css with %s. Ensure %s is writable.',
                    $cssFile->OriginalName(),
                    $target
                );
            }
        }
        if ($faviconImage !== null) {
            Log::Debug('Replacing favicon with ' . $favicon->OriginalName());

            $this->StoreImage(
                baseName: 'custom-favicon',
                extension: $favicon->Extension(),
                contents: $faviconImage,
                removeOld: fn () => $this->RemoveFavicon()
            );
        }
    }

    public function RemoveLogo()
    {
        try {
            $imageUploadDirectory = new ImageUploadDirectory();
            $dirs = [
                $imageUploadDirectory->GetDirectory(),
                ROOT_DIR . 'Web/img',
            ];
            foreach ($dirs as $dir) {
                $targets = glob($dir . '/custom-logo.*');
                foreach ($targets as $target) {
                    $removed = unlink($target);
                    if (!$removed) {
                        Log::Error('Could not remove existing logo. Ensure %s is writable.', $target);
                    }
                }
            }
        } catch (Exception $ex) {
            Log::Error('Could not remove logos. %s', $ex);
        }
    }

    public function RemoveFavicon()
    {
        try {
            $imageUploadDirectory = new ImageUploadDirectory();
            $dirs = [
                $imageUploadDirectory->GetDirectory(),
                ROOT_DIR . 'Web',
            ];
            foreach ($dirs as $dir) {
                $targets = glob($dir . '/custom-favicon.*');
                foreach ($targets as $target) {
                    $removed = unlink($target);
                    if (!$removed) {
                        Log::Error('Could not remove existing favicon. Ensure %s is writable.', $target);
                    }
                }
            }
        } catch (Exception $ex) {
            Log::Error('Could not remove favicon. %s', $ex);
        }
    }

    public function RemoveCss()
    {
        try {
            $targets = glob(ROOT_DIR . 'Web/css/custom-style.css');
            foreach ($targets as $target) {
                $removed = unlink($target);
                if (!$removed) {
                    Log::Error('Could not remove existing css. Ensure %s is writable.', $target);
                }
            }
        } catch (Exception $ex) {
            Log::Error('Could not remove css file. %s', $ex);
        }
    }
    protected function LoadValidators($action)
    {
        $this->page->RegisterValidator('logoFile', new FileUploadValidator($this->page->GetLogoFile()));
        $this->page->RegisterValidator('logoFileExt', new FileTypeValidator(file: $this->page->GetLogoFile(), allowedTypes: ['jpg', 'png', 'gif', 'svg']));
        $this->page->RegisterValidator('cssFile', new FileUploadValidator($this->page->GetCssFile()));
        $this->page->RegisterValidator('cssFileExt', new FileTypeValidator($this->page->GetCssFile(), 'css'));
        $this->page->RegisterValidator('faviconFile', new FileUploadValidator($this->page->GetFaviconFile()));
        $this->page->RegisterValidator('faviconFileExt', new FileTypeValidator(file: $this->page->GetFaviconFile(), allowedTypes: ['ico', 'jpg', 'png', 'gif', 'svg']));
    }

    /**
     * Returns the uploaded image's contents, or null if it is rejected. SVGs are
     * sanitized, because an SVG opened directly runs its scripts on our origin.
     */
    private function ReadImage(UploadedFile $file): ?string
    {
        $contents = file_get_contents(filename: $file->TemporaryName());
        if ($contents === false) {
            Log::Error('Could not read uploaded image %s.', $file->OriginalName());
            return null;
        }
        if ($file->Extension() !== 'svg') {
            return $contents;
        }

        $clean = SvgSanitizer::sanitize(svg: $contents);
        if ($clean === null) {
            Log::Error('Rejected SVG upload %s, it is not a valid SVG image.', $file->OriginalName());
        }

        return $clean;
    }

    /**
     * Writes the new image under a temporary name first and only then removes the old
     * ones, so a failed write keeps the current image.
     */
    private function StoreImage(string $baseName, string $extension, string $contents, callable $removeOld): void
    {
        $uploadDir = (new ImageUploadDirectory())->GetDirectory();
        // Not matched by the "custom-logo.*" / "custom-favicon.*" globs of $removeOld
        $temporary = $uploadDir . '/.upload-' . $baseName;
        $target = $uploadDir . '/' . $baseName . '.' . $extension;

        if (file_put_contents(filename: $temporary, data: $contents) === false) {
            Log::Error('Could not store %s. Ensure %s is writable.', $target, $uploadDir);
            return;
        }

        $removeOld();
        if (!rename(from: $temporary, to: $target)) {
            Log::Error('Could not move %s to %s.', $temporary, $target);
        }
    }
}
