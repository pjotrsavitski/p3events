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

