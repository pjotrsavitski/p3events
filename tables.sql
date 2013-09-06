CREATE TABLE IF NOT EXISTS prefix_p3events_captured_events_log (
    id bigint(20) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    session_id varchar(255),
    timestamp datetime DEFAULT NULL,
    event_type varchar(255),
    actor_guid bigint(20) unsigned,
    actor_data varchar(255),
    object_guid bigint(20) unsigned,
    object_type varchar(255),
    object_subtype varchar(255),
    object_title varchar(255),
    owner_guid bigint(20) unsigned,
    owner_data varchar(255),
    container_guid bigint(20) unsigned,
    container_title varchar(255),
    view_context varchar(255),
    uri varchar(255)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

