# Usage of category tree in tx_rkwevents_domain_model_event TCA
The part to be displayed in the category tree can be defined via TCEFORM. For this, the parent UID of the topmost category must be specified, as well as the STORAGE PIDs of the folders in which the categories were created.

**Example:**

```
# PID's of categories
TCEFORM.tx_rkwevents_domain_model_event.categories.PAGE_TSCONFIG_IDLIST = 2729,2986
TCEFORM.tx_rkwevents_domain_model_event.categories.config.treeConfig.rootUid = 23
```