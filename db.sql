

CREATE TABLE social_account (
    id integer NOT NULL,
    user_id integer,
    provider character varying(255) NOT NULL,
    client_id character varying(255) NOT NULL,
    data text,
    code character varying(32),
    created_at integer,
    email character varying(255),
    username character varying(255)
);



CREATE SEQUENCE account_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE account_id_seq OWNED BY social_account.id;



CREATE TABLE comment (
    id integer NOT NULL,
    entity character varying,
    text text,
    deleted boolean,
    created_by integer,
    updated_by integer,
    created_at integer,
    updated_at integer
);


CREATE SEQUENCE comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE comments_id_seq OWNED BY comment.id;



CREATE TABLE post (
    file character varying,
    thumb character varying,
    owner integer,
    type character varying,
    created_at integer,
    text text,
    tags text,
	deleted boolean DEFAULT false,
	views integer DEFAULT 0,
    id integer NOT NULL
);



CREATE SEQUENCE post_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

	

ALTER SEQUENCE post_id_seq OWNED BY post.id;

ALTER TABLE ONLY post ALTER COLUMN id SET DEFAULT nextval('post_id_seq'::regclass);

ALTER TABLE ONLY post
    ADD CONSTRAINT id PRIMARY KEY (id);





CREATE TABLE profile (
    user_id integer NOT NULL,
    name character varying(255),
    public_email character varying(255),
    gravatar_email character varying(255),
    gravatar_id character varying(32),
    location character varying(255),
    website character varying(255),
    bio text
);



CREATE TABLE token (
    user_id integer NOT NULL,
    code character varying(32) NOT NULL,
    created_at integer NOT NULL,
    type smallint NOT NULL
);




CREATE TABLE "user" (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password_hash character varying(60) NOT NULL,
    auth_key character varying(32) NOT NULL,
    confirmed_at integer,
    unconfirmed_email character varying(255),
    blocked_at integer,
    registration_ip character varying(45),
    created_at integer NOT NULL,
    updated_at integer NOT NULL,
    flags integer DEFAULT 0 NOT NULL
);




CREATE SEQUENCE user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


	
ALTER SEQUENCE user_id_seq OWNED BY "user".id;


ALTER TABLE ONLY comment ALTER COLUMN id SET DEFAULT nextval('comments_id_seq'::regclass);



ALTER TABLE ONLY social_account ALTER COLUMN id SET DEFAULT nextval('account_id_seq'::regclass);


ALTER TABLE ONLY "user" ALTER COLUMN id SET DEFAULT nextval('user_id_seq'::regclass);



ALTER TABLE ONLY social_account
    ADD CONSTRAINT account_pkey PRIMARY KEY (id);




ALTER TABLE ONLY profile
    ADD CONSTRAINT profile_pkey PRIMARY KEY (user_id);


ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


	
CREATE UNIQUE INDEX account_unique ON social_account USING btree (provider, client_id);


CREATE UNIQUE INDEX account_unique_code ON social_account USING btree (code);


CREATE INDEX index_created_at ON comment USING btree (created_at);


CREATE INDEX index_created_by ON comment USING btree (created_by);


CREATE INDEX index_entity ON comment USING btree (entity);


CREATE UNIQUE INDEX token_unique ON token USING btree (user_id, code, type);


CREATE UNIQUE INDEX user_unique_email ON "user" USING btree (email);


CREATE UNIQUE INDEX user_unique_username ON "user" USING btree (username);


ALTER TABLE ONLY social_account
    ADD CONSTRAINT fk_user_account FOREIGN KEY (user_id) REFERENCES "user"(id) ON UPDATE RESTRICT ON DELETE CASCADE;


ALTER TABLE ONLY profile
    ADD CONSTRAINT fk_user_profile FOREIGN KEY (user_id) REFERENCES "user"(id) ON UPDATE RESTRICT ON DELETE CASCADE;


ALTER TABLE ONLY token
    ADD CONSTRAINT fk_user_token FOREIGN KEY (user_id) REFERENCES "user"(id) ON UPDATE RESTRICT ON DELETE CASCADE;


   
CREATE TABLE favorite (
    id serial NOT NULL,
    user_id integer,
    post_owner_id integer,
    post_id integer,
    created_at integer
);
ALTER TABLE ONLY favorite
    ADD CONSTRAINT favorite_pkey PRIMARY KEY (id);
	
	