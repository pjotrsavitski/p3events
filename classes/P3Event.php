<?php

class P3Event {
    protected $session_id;
    protected $timestamp;
    protected $event_type;
    protected $actor_guid;
    protected $actor_data;
    protected $object_guid;
    protected $object_type;
    protected $object_subtype;
    protected $object_title;
    protected $owner_guid;
    protected $owner_data;
    protected $container_guid;
    protected $container_title;
    protected $view_context;
    protected $uri;

    public function __construct() {
        $this->session_id = session_id();
        $this->timestamp = time();
    }

    public function setEventType($event_type) {
        $this->event_type = $event_type;
    }

    public function setActorGuid($actor_guid) {
        $this->actor_guid = $actor_guid;
        $actor = get_user($actor_guid);
        if ($actor) {
            $this->actor_data = sprintf("%s : %s", $actor->username, $actor->name);
        }
        unset($actor);
    }

    public function setObjectGuid($object_guid) {
        $this->object_guid = $object_guid;
    }

    public function setObjectType($object_type) {
        $this->object_type = $object_type;
    }

    public function setObjectSubtype($object_subtype) {
        $this->object_subtype = $object_subtype;
    }

    public function setObjectTitle($object_title) {
        $this->object_title = $object_title;
    }
    
    public function setOwnerGuid($owner_guid) {
        $this->owner_guid = $owner_guid;
        $owner = get_user($owner_guid);
        if ($owner) {
            $this->owner_data = sprintf("%s : %s", $owner->username, $owner->name);
        }
        unset($owner);
    }

    public function setContainerGuid($container_guid) {
        $this->container_guid = $container_guid;
    }

    public function setContainerTitle($container_title) {
        $this->container_title = $container_title;
    }

    public function setViewContext($view_context) {
        $this->view_context = $view_context;
    }

    public function setUri($uri) {
        // TODO This should be a bit better (probably should remove the portal address from the beginning)
        $this->uri = $uri;
    }

    public function save() {
        if ($this->checkIsDuplicateEvent()) {
            return false;
        }
        try {
            global $CONFIG;
            $fields = array();
            $values = array();
            foreach ($this as $field => $value) {
                $fields[] = $field;
                if (is_numeric($value)) {
                    if ('timestamp' == $field) {
                        $values[] = sprintf("FROM_UNIXTIME(%s)", $value);
                    } else {
                        $values[] = $value;
                    }
                } else {
                    $values[] = sprintf("'%s'", sanitise_string($value));
                }
            }

            return insert_data(sprintf("INSERT INTO {$CONFIG->dbprefix}p3events_captured_events_log (%s) VALUES (%s)", implode(", ", $fields), implode(", ", $values)));
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public function checkIsDuplicateEvent() {
        global $CONFIG;
        if (in_array($this->event_type, array('group_update', 'object_update'))) {
            $object = get_data_row(sprintf("SELECT timestamp FROM {$CONFIG->dbprefix}p3events_captured_events_log WHERE actor_guid=%d AND event_type='%s' AND object_guid = %d AND timestamp=FROM_UNIXTIME(%s)", $this->actor_guid, $this->event_type, $this->object_guid, $this->timestamp));
            if ($object && $object instanceof stdClass) {
                return true;
            }
        }
        return false;
    }
}

