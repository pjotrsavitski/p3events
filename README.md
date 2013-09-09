# p3events

## Please DO NOT USE
This plugin should capture data for some events and put it into a standalone database table. The data format is quite specific.

### Log table description (prefix_p3events_captured_events_log)
The data is general and is used for all logged events. Some event might populate more columns than others but in general similar data is placed in the same columns.

* id (integer) - unique identifier for logged event
* session_id (string) - unique current session identifier
* timestamp (date) - timestamp of logged event
* event_type (string) - logged event type
* actor_guid (integer) - unique identifier of current actor
* actor_data (string) - username and name of actor
* object_guid (integer) - unique object identifier
* object_type (string) - logged object type
* object_subtype (string) - logged object subtype
* object_title (string) - logged object title
* owner_guid (integer) - unique identifier of object owner
* owner_data (string) - username and name of owner
* container_guid (integer) - unique identifier of container object
* container_title (string) - logged object container title or name
* view_context (string) - current context
* uri (string) - uri of logged event

### Captured events
A special check is used for group and object update events that are emitted multiple times.

* user_login - user login event with data on user with actor data and URI
* group_crate - group create event with actor, owner and object data and URI
* group_update - group update event with actor, owner and object data and URI
* group_delete - group delete event with actor, owner and objct data and URI
* group_join - group join event with actor, owner and object data and URI
* group_leave - group leave event with actor, owner and object data and URI
* annotation_create - annotation create event with actor, owner, object data and URI. Annotated object data is added as container. Only generic_comment and group_topic_post types are considered.
* annotation_update - annotation update event with actor, owner, object data and URI. Annotated obje
ct data is added as container. Only generic_comment and group_topic_post types are considered.
* annotation_delete - annotation delete event with actor, owner, object data and URI. Annotated obje
ct data is added as container. Only generic_comment and group_topic_post types are considered.
* object_create - object create event with actor, owner and object data with URI. Container data is populated with ElggGroup data in case group is set as container.
* object_update - object update event with actor, owner and object data with URI. Container data is
populated with ElggGroup data in case group is set as container.
* object_delete - object delete event with actor, owner and object data with URI. Container data is
populated with ElggGroup data in case group is set as container.
* view - actor, context and URI data is saved. In case certain types of objects are viewed additional data is being captured if object can be found by id: owner, object and container (if ElggGroup is a container) data. The data captured in case: non-empty context, context not being part of excluded ones, user being loggedin.

