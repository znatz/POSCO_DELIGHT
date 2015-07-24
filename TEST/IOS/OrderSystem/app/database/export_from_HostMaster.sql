CREATE TABLE "users_groups" ("user_id" integer not null, "group_id" integer not null, primary key ("user_id", "group_id"));
CREATE TABLE "users" ("id" integer not null primary key autoincrement, "email" varchar not null, "password" varchar not null, "permissions" text null, "activated" tinyint not null default '0', "activation_code" varchar null, "activated_at" datetime null, "last_login" datetime null, "persist_code" varchar null, "reset_password_code" varchar null, "first_name" varchar null, "last_name" varchar null, "created_at" datetime not null, "updated_at" datetime not null);
CREATE TABLE "throttle" ("id" integer not null primary key autoincrement, "user_id" integer null, "ip_address" varchar null, "attempts" integer not null default '0', "suspended" tinyint not null default '0', "banned" tinyint not null default '0', "last_attempt_at" datetime null, "suspended_at" datetime null, "banned_at" datetime null);
CREATE TABLE "groups" ("id" integer not null primary key autoincrement, "name" varchar not null, "permissions" text null, "created_at" datetime not null, "updated_at" datetime not null);
CREATE INDEX users_reset_password_code_index on "users" ("reset_password_code");
CREATE UNIQUE INDEX users_email_unique on "users" ("email");
CREATE INDEX users_activation_code_index on "users" ("activation_code");
CREATE INDEX throttle_user_id_index on "throttle" ("user_id");
CREATE UNIQUE INDEX groups_name_unique on "groups" ("name");
