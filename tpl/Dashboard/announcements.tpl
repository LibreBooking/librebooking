<div class="accordion-item shadow mb-2" id="announcementsDashboard">
	<div class="accordion-header dashboardHeader">
		<button class="accordion-button collapsed link-primary fw-bold" type="button" data-bs-toggle="collapse"
			data-bs-target="#announcementsContents" aria-expanded="false" aria-controls="announcementsContents">
			{translate key="Announcements"} <span
				class="badge bg-primary ms-1">{$Announcements|default:array()|count}</span>
		</button>
	</div>
	<div id="announcementsContents" class="accordion-collapse collapse">
		<div class="accordion-body">
			<div class="announcementContent">
				{foreach from=$Announcements item=each}
					<div class="border-bottom py-2">{$each->Text()|html_entity_decode|url2link|nl2br}</div>
				{foreachelse}
					<p class="noresults text-center fst-italic fs-5">{translate key="NoAnnouncements"}</p>
				{/foreach}
				</ul>
			</div>
		</div>
	</div>
</div>