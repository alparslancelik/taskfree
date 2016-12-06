CREATE TABLE tf_category (
	cid SERIAL PRIMARY KEY,
	name VARCHAR(256) NOT NULL UNIQUE,
	description VARCHAR(256) NOT NULL,
	image_url VARCHAR(256) NOT NULL
);


CREATE TABLE tf_user (
	uid SERIAL PRIMARY KEY,
	name VARCHAR(256) NOT NULL,
	email VARCHAR(256) NOT NULL UNIQUE,
	uname VARCHAR(256) NOT NULL UNIQUE,
	password VARCHAR(256) NOT NULL,
	admin BOOLEAN NOT NULL DEFAULT 'false',
	image_url VARCHAR(256)
);


CREATE TABLE tf_task (
	tid SERIAL PRIMARY KEY,
	creator INTEGER REFERENCES tf_user(uid),
	start_time TIMESTAMP NOT NULL,
	end_time TIMESTAMP,
	title VARCHAR(256) NOT NULL,
	description VARCHAR(256) NOT NULL,
	completed BOOLEAN NOT NULL DEFAULT 'false',
	longitude FLOAT,
	latitude FLOAT,
	npeople INTEGER NOT NULL,
	cid INTEGER REFERENCES tf_category(cid) ON DELETE CASCADE,
	step BOOLEAN,
	CHECK (end_time > start_time)
);


CREATE TABLE tf_pick (
	score INTEGER,
	accepted BOOLEAN NOT NULL DEFAULT 'false',
	comment VARCHAR(500) DEFAULT NULL,
	uid INTEGER REFERENCES tf_user(uid) ON DELETE CASCADE,
	tid INTEGER REFERENCES tf_task(tid) ON DELETE CASCADE,
	PRIMARY KEY(uid,tid),
	CHECK (score >= 0 AND score <= 10)
);


CREATE TABLE tf_step (
	sid INTEGER,
	tid INTEGER REFERENCES tf_task(tid) ON DELETE CASCADE,
	description VARCHAR(256),
	longitude FLOAT,
	latitude FLOAT,
	completed BOOLEAN NOT NULL DEFAULT 'false',
	PRIMARY KEY(sid, tid)
);


CREATE TABLE tf_history (
	hid SERIAL PRIMARY KEY,
	time TIMESTAMP NOT NULL,
	u1id INTEGER,
	u1name VARCHAR(256),
	u2id INTEGER,
	u2name VARCHAR(256),
	tid INTEGER,
	ttitle VARCHAR(256),
	type CHAR(4) CHECK(type = 'pick' OR type = 'rate')
);

CREATE VIEW temp AS
SELECT u.uname, AVG(p.score)
FROM tf_user u, tf_pick p, tf_task t
WHERE u.uid=p.uid AND p.accepted=TRUE AND p.tid=t.tid AND t.completed=TRUE
GROUP BY u.uname;

CREATE OR REPLACE FUNCTION updateaccept () RETURNS TRIGGER
AS $functionname$
DECLARE
name VARCHAR(256);
uid INTEGER;
name2 VARCHAR(256);
title VARCHAR(256);
BEGIN
name = (SELECT u.name FROM tf_user u WHERE u.uid=new.uid);
uid = (SELECT t.creator FROM tf_task t WHERE t.tid=new.tid);
name2 = (SELECT u.name FROM tf_user u, tf_task t WHERE new.tid=t.tid AND t.creator=u.uid);
title = (SELECT t.title FROM tf_task t WHERE t.tid=new.tid);

INSERT into tf_history (time, u1id, u1name, u2id, u2name, tid, ttitle, type) VALUES (now(), new.uid, name, uid, name2, new.tid, title, 'pick');
RETURN NULL;
END;
$functionname$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION updaterate () RETURNS TRIGGER
AS $functionname$
DECLARE
name VARCHAR(256);
uid INTEGER;
name2 VARCHAR(256);
title VARCHAR(256);
BEGIN
name = (SELECT u.name FROM tf_user u WHERE u.uid=new.uid);
uid = (SELECT t.creator FROM tf_task t WHERE t.tid=new.tid);
name2 = (SELECT u.name FROM tf_user u, tf_task t WHERE new.tid=t.tid AND t.creator=u.uid);
title = (SELECT t.title FROM tf_task t WHERE t.tid=new.tid);

INSERT into tf_history (time, u1id, u1name, u2id, u2name, tid, ttitle, type) VALUES (now(), new.uid, name, uid, name2, new.tid, title, 'rate');
RETURN NULL;
END;
$functionname$ LANGUAGE plpgsql;

CREATE trigger t1 AFTER
UPDATE on tf_pick
FOR EACH ROW
WHEN (OLD.accpeted IS DISTINCT FROM NEW.accpeted)
EXECUTE procedure updateaccept();

CREATE trigger t2 AFTER
UPDATE on tf_pick
FOR EACH ROW
WHEN (OLD.score IS DISTINCT FROM NEW.score OR OLD.comment IS DISTINCT FROM NEW.comment)
EXECUTE procedure updaterate();
