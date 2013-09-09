<?php

function p3events_init() {

    // Register event handlers

    // User events
    elgg_register_event_handler('login', 'user', 'p3events_log_user_login');

    // Group events
    elgg_register_event_handler('create', 'group', 'p3events_log_group_create');
    elgg_register_event_handler('update', 'group', 'p3events_log_group_update');
    elgg_register_event_handler('delete', 'group', 'p3events_log_group_delete');
    elgg_register_event_handler('join', 'group', 'p3events_log_group_join');
    elgg_register_event_handler('leave', 'group', 'p3events_log_group_leave');

    // Annotation events
    elgg_register_event_handler('create', 'annotation', 'p3events_log_annotation_create');
    elgg_register_event_handler('update', 'annotation', 'p3events_log_annotation_update');
    elgg_register_event_handler('delete', 'annotation', 'p3events_log_annotation_delete');

    // Object events
    elgg_register_event_handler('create', 'object', 'p3events_log_object_create');
    elgg_register_event_handler('update', 'object', 'p3events_log_object_update');
    elgg_register_event_handler('delete', 'object', 'p3events_log_object_delete');

    // View events
    elgg_register_event_handler('shutdown', 'system', 'p3events_log_system_shutdown');
}

elgg_register_event_handler('init', 'system', 'p3events_init');

function p3events_log_user_login($event, $type, $params) {
    if ($params instanceof ElggUser) {
        $p3event = new P3Event();
        $p3event->setActorGuid($params->guid);
        $p3event->setUri($params->getURL());
        $p3event->setEventType('user_login');
        $p3event->setObjectType($type);
        $p3event->save();
        unset($p3event);
    }
}

function p3events_log_group_create($event, $type, $params) {
    if ($params instanceof ElggGroup) {
        $loggedin = elgg_get_logged_in_user_entity();
        $p3event = new P3Event();
        $p3event->setActorGuid($loggedin->getGUID());
        $p3event->setOwnerGuid($loggedin->getGUID());
        $p3event->setObjectGuid($params->getGUID());
        $p3event->setObjectType($params->getType());
        $p3event->setObjectSubtype($params->getSubtype());
        $p3event->setObjectTitle($params->name);
        $p3event->setEventType('group_create');
        $p3event->setUri($params->getURL());
        $p3event->save();
        unset($loggedin);
        unset($p3event);
    }
}

function p3events_log_group_update($event, $type, $params) {
    if ($params instanceof ElggGroup) {
        $loggedin = elgg_get_logged_in_user_entity();
        $owner = $params->getOwnerEntity();
        $p3event = new P3Event();
        $p3event->setActorGuid($loggedin->getGUID());
        $p3event->setOwnerGuid($owner->getGUID());
        $p3event->setObjectGuid($params->getGUID());
        $p3event->setObjectType($params->getType());
        $p3event->setObjectSubtype($params->getSubtype());
        $p3event->setObjectTitle($params->name);
        $p3event->setEventType('group_update');
        $p3event->setUri($params->getURL());
        $p3event->save();
        unset($loggedin);
        unset($owner);
        unset($p3event);
    }
}

function p3events_log_group_delete($event, $type, $params) {
    if ($params instanceof ElggGroup) {
        $loggedin = elgg_get_logged_in_user_entity();
        $owner = $params->getOwnerEntity();
        $p3event = new P3Event();
        $p3event->setActorGuid($loggedin->getGUID());
        $p3event->setOwnerGuid($owner->getGUID());
        $p3event->setObjectGuid($params->getGUID());
        $p3event->setObjectType($params->getType());
        $p3event->setObjectSubtype($params->getSubtype());
        $p3event->setObjectTitle($params->name);
        $p3event->setEventType('group_delete');
        $p3event->setUri($params->getURL());
        $p3event->save();
        unset($loggedin);
        unset($owner);
        unset($p3event);
    }
}

function p3events_log_group_join($event, $type, $params) {
    if (is_array($params) && sizeof($params) == 2 && $params['group'] instanceof ElggGroup && $params['user'] instanceof ElggUser) {
        $p3event = new P3Event();
        $p3event->setActorGuid($params['user']->getGUID());
        $p3event->setOwnerGuid($params['group']->getOwnerEntity()->getGUID());
        $p3event->setObjectGuid($params['group']->getGUID());
        $p3event->setObjectType($params['group']->getType());
        $p3event->setObjectSubtype($params['group']->getSubtype());
        $p3event->setObjectTitle($params['group']->name);
        $p3event->setEventType('group_join');
        $p3event->setUri($params['group']->getURL());
        $p3event->save();
        unset($p3event);
    }
}

function p3events_log_group_leave($event, $type, $params) {
    if (is_array($params) && sizeof($params) == 2 && $params['group'] instanceof ElggGroup && $params['user'] instanceof ElggUser) {
        $p3event = new P3Event();
        $p3event->setActorGuid($params['user']->getGUID());
        $p3event->setOwnerGuid($params['group']->getOwnerEntity()->getGUID());
        $p3event->setObjectGuid($params['group']->getGUID());
        $p3event->setObjectType($params['group']->getType());
        $p3event->setObjectSubtype($params['group']->getSubtype());
        $p3event->setObjectTitle($params['group']->name);
        $p3event->setEventType('group_leave');
        $p3event->setUri($params['group']->getURL());
        $p3event->save();
        unset($p3event);
    }
}

function p3events_log_annotation_create($event, $type, $params) {
    if ($params instanceof ElggAnnotation) {
        if (in_array($params->getSubtype(), array('generic_comment', 'group_topic_post'))) {
            $loggedin = elgg_get_logged_in_user_entity();
            $creator = $params->getOwnerEntity();
            $entity = $params->getEntity();
            $p3event = new P3Event();
            $p3event->setActorGuid($loggedin->getGUID());
            $p3event->setOwnerGuid($creator->getGUID());
            $p3event->setObjectGuid($params->id);
            $p3event->setObjectType($params->getType());
            $p3event->setObjectSubtype($params->getSubtype());
            $p3event->setContainerGuid($entity->getGUID());
            $p3event->setContainerTitle($entity->title);
            $p3event->setUri($params->getURL());
            $p3event->setEventType('annotation_create');
            $p3event->save();
            unset($loggedin);
            unset($creator);
            unset($entity);
        }
    }
}

function p3events_log_annotation_update($event, $type, $params) {
    if ($params instanceof ElggAnnotation) {
        if (in_array($params->getSubtype(), array('generic_comment', 'group_topic_post'))) {
            $loggedin = elgg_get_logged_in_user_entity();
            $creator = $params->getOwnerEntity();
            $entity = $params->getEntity();
            $p3event = new P3Event();
            $p3event->setActorGuid($loggedin->getGUID());
            $p3event->setOwnerGuid($creator->getGUID());
            $p3event->setObjectGuid($params->id);
            $p3event->setObjectType($params->getType());
            $p3event->setObjectSubtype($params->getSubtype());
            $p3event->setContainerGuid($entity->getGUID());
            $p3event->setContainerTitle($entity->title);
            $p3event->setUri($params->getURL());
            $p3event->setEventType('annotation_update');
            $p3event->save();
            unset($loggedin);
            unset($creator);
            unset($entity);
        }
    }
}

function p3events_log_annotation_delete($event, $type, $params) {
    if ($params instanceof ElggAnnotation) {
        if (in_array($params->getSubtype(), array('generic_comment', 'group_topic_post'))) {
            $loggedin = elgg_get_logged_in_user_entity();
            $creator = $params->getOwnerEntity();
            $entity = $params->getEntity();
            $p3event = new P3Event();
            $p3event->setActorGuid($loggedin->getGUID());
            $p3event->setOwnerGuid($creator->getGUID());
            $p3event->setObjectGuid($params->id);
            $p3event->setObjectType($params->getType());
            $p3event->setObjectSubtype($params->getSubtype());
            $p3event->setContainerGuid($entity->getGUID());
            $p3event->setContainerTitle($entity->title);
            $p3event->setUri($params->getURL());
            $p3event->setEventType('annotation_delete');
            $p3event->save();
            unset($loggedin);
            unset($creator);
            unset($entity);
        }
    }
}

function p3events_log_object_create($event, $type, $params) {
    if ($params instanceof ElggObject) {
        $p3event = new P3Event();
        $p3event->setActorGuid($params->getOwnerEntity()->getGUID());
        $p3event->setOwnerGuid($params->getOwnerEntity()->getGUID());
        $p3event->setObjectGuid($params->getGUID());
        $p3event->setObjectType($params->getType());
        $p3event->setObjectSubtype($params->getSubtype());
        $p3event->setObjectTitle($params->title);
        $p3event->setUri($params->getURL());
        $container = $params->getContainerEntity();
        if ($container instanceof ElggGroup) {
            $p3event->setContainerGuid($container->getGUID());
            $p3event->setContainerTitle($container->name);
        }
        $p3event->setEventType('object_create');
        $p3event->save();
        unset($p3event);
    }
}

function p3events_log_object_update($event, $type, $params) {
    if ($params instanceof ElggObject) {
        $loggedin = elgg_get_logged_in_user_entity();
        $p3event = new P3Event();
        $p3event->setActorGuid($loggedin->getGUID());
        $p3event->setOwnerGuid($params->getOwnerEntity()->getGUID());
        $p3event->setObjectGuid($params->getGUID());
        $p3event->setObjectType($params->getType());
        $p3event->setObjectSubtype($params->getSubtype());
        $p3event->setObjectTitle($params->title);
        $p3event->setUri($params->getURL());
        $container = $params->getContainerEntity();
        if ($container instanceof ElggGroup) {
            $p3event->setContainerGuid($container->getGUID());
            $p3event->setContainerTitle($container->name);
        }
        $p3event->setEventType('object_update');
        $p3event->save();
        unset($loggedin);
        unset($p3event);
    }
}

function p3events_log_object_delete($event, $type, $params) {
    if ($params instanceof ElggObject) {
        $loggedin = elgg_get_logged_in_user_entity();
        $p3event = new P3Event();
        $p3event->setActorGuid($loggedin->getGUID());
        $p3event->setOwnerGuid($params->getOwnerEntity()->getGUID());
        $p3event->setObjectGuid($params->getGUID());
        $p3event->setObjectType($params->getType());
        $p3event->setObjectSubtype($params->getSubtype());
        $p3event->setObjectTitle($params->title);
        $p3event->setUri($params->getURL());
        $container = $params->getContainerEntity();
        if ($container instanceof ElggGroup) {
            $p3event->setContainerGuid($container->getGUID());
            $p3event->setContainerTitle($container->name);
        }
        $p3event->setEventType('object_delete');
        $p3event->save();
        unset($loggedin);
        unset($p3event);
    }
}

function p3events_log_system_shutdown($event, $type, $params) {
    $context = elgg_get_context();
    $uri = current_page_url();

    if (
        !empty($context)
        && !in_array($context, array('captcha', 'api', 'admin', 'ajax'))
        && elgg_is_logged_in()
    ) {
        $loggedin = elgg_get_logged_in_user_entity();
        $p3event = new P3Event();
        $p3event->setActorGuid($loggedin->getGUID());
        $p3event->setViewContext($context);
        $p3event->setUri($uri);
        $p3event->setEventType('view');
        $p3event->save();
        unset($p3event);
    }
    unset($context);
    unset($uri);
}

