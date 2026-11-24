<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'Presenters/SearchAvailabilityPresenter.php');

class SearchAvailabilityResultsTemplateTest extends TestBase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->fakeResources->SetDateFormat('schedule_daily', 'm-d');
        $this->fakeResources->SetDateFormat('period_time', 'H:i');
        $this->fakeResources->SetDateFormat('short_reservation_date', 'Y-m-d H:i');
    }

    public function testGroupsOpeningsByResourceAndDateWithLocalizedLabelsAndAttributes(): void
    {
        $first = new TestResourceDto(id: 1, name: 'Room');
        $second = new TestResourceDto(id: 2, name: 'Room');
        $openings = [
            $this->opening(resource: $first, start: '2025-01-15 09:00:00', end: '2025-01-15 10:00:00'),
            $this->opening(resource: $second, start: '2025-01-15 11:00:00', end: '2025-01-15 12:00:00'),
            $this->opening(resource: $first, start: '2026-01-15 14:00:00', end: '2026-01-15 15:00:00'),
            $this->opening(resource: $first, start: '2025-01-15 23:00:00', end: '2025-01-16 01:00:00'),
            $this->opening(resource: $second, start: '2025-01-16 13:00:00', end: '2025-01-16 14:00:00'),
        ];

        $xpath = $this->render(openings: $openings);
        $resources = $xpath->query(expression: '//li[contains(concat(" ", normalize-space(@class), " "), " list-group-item ")]');
        $this->assertNotFalse($resources);
        $this->assertCount(2, $resources);

        $actual = [];
        foreach ($resources as $resource) {
            $resourceNames = $xpath->query(
                expression: './/span[contains(concat(" ", normalize-space(@class), " "), " resourceName ")]',
                contextNode: $resource
            );
            $this->assertNotFalse($resourceNames);
            $this->assertCount(1, $resourceNames);
            $resourceName = $resourceNames->item(0);
            $this->assertInstanceOf(DOMElement::class, $resourceName);

            $days = $xpath->query(
                expression: './/div[contains(concat(" ", normalize-space(@class), " "), " row ")]',
                contextNode: $resource
            );
            $this->assertNotFalse($days);

            $renderedDays = [];
            foreach ($days as $day) {
                $labels = $xpath->query(
                    expression: './div[contains(concat(" ", normalize-space(@class), " "), " fw-semibold ")]',
                    contextNode: $day
                );
                $this->assertNotFalse($labels);
                $this->assertCount(1, $labels);

                $slots = $xpath->query(
                    expression: './/span[contains(concat(" ", normalize-space(@class), " "), " opening ")]',
                    contextNode: $day
                );
                $this->assertNotFalse($slots);
                $renderedSlots = [];
                foreach ($slots as $slot) {
                    $this->assertInstanceOf(DOMElement::class, $slot);
                    $renderedSlots[] = [
                        $slot->getAttribute(qualifiedName: 'data-resourceid'),
                        $slot->getAttribute(qualifiedName: 'data-startdate'),
                        $slot->getAttribute(qualifiedName: 'data-enddate'),
                        $xpath->evaluate(expression: 'normalize-space(.)', contextNode: $slot),
                    ];
                }

                $renderedDays[] = [trim(string: $labels->item(0)->textContent), $renderedSlots];
            }

            $actual[] = [
                $resourceName->getAttribute(qualifiedName: 'data-resourceid'),
                trim(string: $resourceName->textContent),
                $renderedDays,
            ];
        }

        $this->assertSame([
            ['1', 'Room', [
                ['01-15', [
                    ['1', '2025-01-15 09:00:00', '2025-01-15 10:00:00', '09:00 - 10:00'],
                    ['1', '2025-01-15 23:00:00', '2025-01-16 01:00:00', '23:00 - 2025-01-16 01:00'],
                ]],
                ['01-15', [
                    ['1', '2026-01-15 14:00:00', '2026-01-15 15:00:00', '14:00 - 15:00'],
                ]],
            ]],
            ['2', 'Room', [
                ['01-15', [
                    ['2', '2025-01-15 11:00:00', '2025-01-15 12:00:00', '11:00 - 12:00'],
                ]],
                ['01-16', [
                    ['2', '2025-01-16 13:00:00', '2025-01-16 14:00:00', '13:00 - 14:00'],
                ]],
            ]],
        ], $actual);
    }

    public function testShowsEmptyStateWithoutResourceGroupsOrOpenings(): void
    {
        $xpath = $this->render(openings: []);
        $warnings = $xpath->query(expression: '//div[contains(concat(" ", normalize-space(@class), " "), " bg-warning-subtle ")]');
        $this->assertNotFalse($warnings);
        $this->assertCount(1, $warnings);
        $this->assertStringContainsString('NoAvailableMatchingTimes', $warnings->item(0)->textContent);

        $groups = $xpath->query(expression: '//li[contains(concat(" ", normalize-space(@class), " "), " list-group-item ")]');
        $this->assertNotFalse($groups);
        $this->assertCount(0, $groups);
        $slots = $xpath->query(expression: '//span[contains(concat(" ", normalize-space(@class), " "), " opening ")]');
        $this->assertNotFalse($slots);
        $this->assertCount(0, $slots);
    }

    private function opening(TestResourceDto $resource, string $start, string $end): AvailableOpeningView
    {
        return new AvailableOpeningView(
            resource: $resource,
            start: Date::Parse(dateString: $start, timezone: 'UTC'),
            end: Date::Parse(dateString: $end, timezone: 'UTC')
        );
    }

    /** @param AvailableOpeningView[] $openings */
    private function render(array $openings): DOMXPath
    {
        $page = new SmartyPage();
        $page->assign('Openings', $openings);

        $document = new DOMDocument();
        $document->loadHTML(source: $page->fetch('SearchAvailability/search-availability-results.tpl'));

        return new DOMXPath(document: $document);
    }
}
