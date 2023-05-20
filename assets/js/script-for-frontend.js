
/**
 * Activities: add heading to meedoen-link for contactform 
 * 
 * We gaan uit van de volgende structuur
 * 
 * Activiteitengroep 
 *   - met `id=activiteiten`
 * Activiteit
 * 	 - met class `is-style-activity`
 *   - met inhoud Heading-block
 *   - met inhoud link naar `meedoen`
 * Contactpagina
 *   - met url waarin `meedoen` naar voren komt
 *   - met contactformulierveld `your-subject`
 */
	
let activitiesGroup = document.querySelector( '#activiteiten' );
let activities;
let activity;
let activityTitle;
let activityAnchor;
let activityAnchorLink;

if (activitiesGroup) {
	
	let activities = activitiesGroup.querySelectorAll('.is-style-activity');

	if (activities) {

		// Remove negative tabindexes set by methods.preventFocusInTarget()
		for (var i = 0; i < activities.length; i++) {

			activityTitle = activities[i].querySelector( '.wp-block-heading' );
			activityAnchor = activities[i].querySelector( 'a[href*="meedoen"]' );

			if (activityTitle && activityAnchor) {

				// Use the URL api
				activityAnchorLink = new URL(activityAnchor.href);

				// Add parameter to the link
				activityAnchorLink.searchParams.append('your-subject', 'Activiteit: ' + activityTitle.textContent);

				// Update the href with the appended link
				activityAnchor.href = activityAnchorLink;
			}
		}
	}
}