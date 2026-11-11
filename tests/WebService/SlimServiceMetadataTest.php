<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/WebService/Slim/namespace.php');

class SlimServiceMetadataTestHandlers
{
    /**
     * @name Tagged
     * @description Does something
     * across two lines
     * @request RestResponse
     * @response RestResponse
     * @return void
     */
    public function Tagged(): void
    {
    }

    /**
     * Summary line before the tags.
     * @name LeadingSummary
     * @response RestResponse
     * @return void
     */
    public function LeadingSummary(): void
    {
    }

    /**
     * @name BlankLine
     *
     * @response RestResponse
     * @return void
     */
    public function BlankLine(): void
    {
    }
}

class SlimServiceMetadataTest extends TestBase
{
    /**
     * A docblock that the parser cannot get past would loop forever, so
     * cap the run time: a regression ends the run with a fatal "Maximum
     * execution time" error instead of hanging CI.
     */
    private const PARSE_TIME_LIMIT_SECONDS = 10;

    private int $previousTimeLimit;

    public function setUp(): void
    {
        parent::setup();
        $this->previousTimeLimit = (int) ini_get('max_execution_time');
        set_time_limit(seconds: self::PARSE_TIME_LIMIT_SECONDS);
    }

    public function tearDown(): void
    {
        set_time_limit(seconds: $this->previousTimeLimit);
        parent::tearDown();
    }

    public function testParsesTags(): void
    {
        $metadata = new SlimServiceMetadata([SlimServiceMetadataTestHandlers::class, 'Tagged']);

        $this->assertSame('Tagged', $metadata->name);
        $this->assertSame("Does something\nacross two lines", $metadata->description);
        $this->assertSame(['type' => 'RestResponse'], $metadata->request);
        $this->assertSame(['type' => 'RestResponse'], $metadata->return);
    }

    public function testSkipsSummaryLineBeforeTags(): void
    {
        $metadata = new SlimServiceMetadata([SlimServiceMetadataTestHandlers::class, 'LeadingSummary']);

        $this->assertSame('LeadingSummary', $metadata->name);
        $this->assertSame(['type' => 'RestResponse'], $metadata->return);
    }

    public function testSkipsBlankLineBetweenTags(): void
    {
        $metadata = new SlimServiceMetadata([SlimServiceMetadataTestHandlers::class, 'BlankLine']);

        $this->assertSame('BlankLine', $metadata->name);
        $this->assertSame(['type' => 'RestResponse'], $metadata->return);
    }
}
