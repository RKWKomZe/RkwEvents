# Usage of category tree in tx_rkwevents_domain_model_event TCA
The part to be displayed in the category tree can be defined via TCEFORM. For this, the parent UID of the topmost category must be specified, as well as the STORAGE PIDs of the folders in which the categories were created.

**Example:**

```
# PID's of categories
TCEFORM.tx_rkwevents_domain_model_event.categories.PAGE_TSCONFIG_IDLIST = 2729,2986
TCEFORM.tx_rkwevents_domain_model_event.categories.config.treeConfig.rootUid = 23
```

## Add newsletter subscription field ##

You can add a checkbox to an event reservation in order to subscribe to a newsletter. The confirmation will be sent within the corresponding confirmation and notification mails and the real adding to the subscription will be done manually by the backoffice.

In order to activate it and to control the wording you may add the following to the Typoscript settings of the corresponding root page:

```
plugin.tx_rkwevents {
	settings {
		showNewsletterCheckbox = 1
	}
	_LOCAL_LANG.de {
		tx_rkwevents_fluid.partials_eventReservation_formFieldsMaster.subscribeNewsletter = [YOUR TEXT]
	}
}
```
