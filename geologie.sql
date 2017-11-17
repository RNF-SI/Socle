--
-- PostgreSQL database dump
--

-- Dumped from database version 10.0
-- Dumped by pg_dump version 10.0

-- Started on 2017-11-17 11:57:46

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 1 (class 3079 OID 12924)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 4423 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- TOC entry 2 (class 3079 OID 145589)
-- Name: postgis; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA public;


--
-- TOC entry 4424 (class 0 OID 0)
-- Dependencies: 2
-- Name: EXTENSION postgis; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis IS 'PostGIS geometry, geography, and raster spatial types and functions';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 212 (class 1259 OID 147088)
-- Name: ci_sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ci_sessions (
    id character varying(128) NOT NULL,
    ip_address character varying(45) NOT NULL,
    "timestamp" bigint DEFAULT 0 NOT NULL,
    data text DEFAULT ''::text NOT NULL
);


ALTER TABLE ci_sessions OWNER TO postgres;

--
-- TOC entry 213 (class 1259 OID 147096)
-- Name: commentaire; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE commentaire (
    id integer NOT NULL,
    espace_protege_id integer NOT NULL,
    rubrique character varying(50),
    commentaire text
);


ALTER TABLE commentaire OWNER TO postgres;

--
-- TOC entry 4425 (class 0 OID 0)
-- Dependencies: 213
-- Name: TABLE commentaire; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE commentaire IS 'commentaires par rubrique';


--
-- TOC entry 214 (class 1259 OID 147102)
-- Name: commentaire_eg; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE commentaire_eg (
    id integer NOT NULL,
    entite_geol_id integer NOT NULL,
    rubrique character varying(50),
    commentaire text
);


ALTER TABLE commentaire_eg OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 147108)
-- Name: commentaire_eg_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE commentaire_eg_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE commentaire_eg_id_seq OWNER TO postgres;

--
-- TOC entry 4426 (class 0 OID 0)
-- Dependencies: 215
-- Name: commentaire_eg_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE commentaire_eg_id_seq OWNED BY commentaire_eg.id;


--
-- TOC entry 216 (class 1259 OID 147110)
-- Name: commentaire_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE commentaire_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE commentaire_id_seq OWNER TO postgres;

--
-- TOC entry 4427 (class 0 OID 0)
-- Dependencies: 216
-- Name: commentaire_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE commentaire_id_seq OWNED BY commentaire.id;


--
-- TOC entry 217 (class 1259 OID 147112)
-- Name: complement; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE complement (
    id integer NOT NULL,
    espace_protege_id integer NOT NULL,
    question character varying(15),
    elements text
);


ALTER TABLE complement OWNER TO postgres;

--
-- TOC entry 4428 (class 0 OID 0)
-- Dependencies: 217
-- Name: TABLE complement; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE complement IS 'sert à mentionner les éléments complémentaires pour une question  donnée';


--
-- TOC entry 218 (class 1259 OID 147118)
-- Name: complement_eg; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE complement_eg (
    id integer NOT NULL,
    entite_geol_id integer NOT NULL,
    question character varying(15),
    elements text
);


ALTER TABLE complement_eg OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 147124)
-- Name: complement_eg_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE complement_eg_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE complement_eg_id_seq OWNER TO postgres;

--
-- TOC entry 4429 (class 0 OID 0)
-- Dependencies: 219
-- Name: complement_eg_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE complement_eg_id_seq OWNED BY complement_eg.id;


--
-- TOC entry 220 (class 1259 OID 147126)
-- Name: echelle_geol; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE echelle_geol (
    id integer NOT NULL,
    label character varying,
    parent integer
);


ALTER TABLE echelle_geol OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 147132)
-- Name: element_supplementaire_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE element_supplementaire_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE element_supplementaire_id_seq OWNER TO postgres;

--
-- TOC entry 4430 (class 0 OID 0)
-- Dependencies: 221
-- Name: element_supplementaire_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE element_supplementaire_id_seq OWNED BY complement.id;


--
-- TOC entry 222 (class 1259 OID 147134)
-- Name: emprise_cartes_geol; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE emprise_cartes_geol (
    id integer NOT NULL,
    geom geometry(Polygon,4326),
    nom character varying,
    numero integer
);


ALTER TABLE emprise_cartes_geol OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 147140)
-- Name: emprise_cartes_geol_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE emprise_cartes_geol_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE emprise_cartes_geol_id_seq OWNER TO postgres;

--
-- TOC entry 4431 (class 0 OID 0)
-- Dependencies: 223
-- Name: emprise_cartes_geol_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE emprise_cartes_geol_id_seq OWNED BY emprise_cartes_geol.id;


--
-- TOC entry 224 (class 1259 OID 147142)
-- Name: entite_geol; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE entite_geol (
    id integer NOT NULL,
    intitule character varying NOT NULL,
    code_eg character varying(10),
    intitule_eg character varying,
    id_ere_geol integer,
    quantite_affleurements character varying(50),
    affleurements_accessibles boolean,
    permeabilite character varying(50),
    presence_aquifere boolean,
    niveau_sources boolean,
    complements text,
    espace_protege_id integer NOT NULL,
    geom geometry(MultiPoint,4326)
);


ALTER TABLE entite_geol OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 147148)
-- Name: entite_geol_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE entite_geol_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE entite_geol_id_seq OWNER TO postgres;

--
-- TOC entry 4432 (class 0 OID 0)
-- Dependencies: 225
-- Name: entite_geol_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE entite_geol_id_seq OWNED BY entite_geol.id;


--
-- TOC entry 226 (class 1259 OID 147150)
-- Name: entite_geol_qcm; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE entite_geol_qcm (
    entite_geol_id integer NOT NULL,
    qcm_id integer NOT NULL,
    info_complement character varying,
    patrimonial boolean DEFAULT false NOT NULL
);


ALTER TABLE entite_geol_qcm OWNER TO postgres;

--
-- TOC entry 4433 (class 0 OID 0)
-- Dependencies: 226
-- Name: TABLE entite_geol_qcm; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE entite_geol_qcm IS 'table de liaison EG-QCM';


--
-- TOC entry 227 (class 1259 OID 147153)
-- Name: espace_protege_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE espace_protege_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE espace_protege_id_seq OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 147155)
-- Name: espace_protege; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE espace_protege (
    id integer DEFAULT nextval('espace_protege_id_seq'::regclass) NOT NULL,
    code_national_ep character varying(255) NOT NULL,
    nom_ep character varying(255) NOT NULL,
    type_ep character varying(255) NOT NULL,
    sous_type_ep character varying(255),
    numero_ep integer,
    surface_ep double precision,
    altitude_max_ep double precision,
    altitude_min_ep double precision,
    nombre_morcellement integer DEFAULT 1,
    bassin_hydro_general character varying(255) DEFAULT NULL::character varying,
    bassin_hydro_rapproche character varying(255) DEFAULT NULL::character varying,
    observations_in_situ text,
    liste_docs_geol text,
    autres_cartes_geol text,
    statut_validation character varying(20),
    group_id integer
);


ALTER TABLE espace_protege OWNER TO postgres;

--
-- TOC entry 4434 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN espace_protege.group_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN espace_protege.group_id IS 'identifiant du groupe "propriétaire" de l''espace';


--
-- TOC entry 229 (class 1259 OID 147165)
-- Name: espace_protege_qcm; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE espace_protege_qcm (
    espace_protege_id integer NOT NULL,
    qcm_id integer NOT NULL,
    info_complement character varying,
    patrimonial boolean DEFAULT false NOT NULL
);


ALTER TABLE espace_protege_qcm OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 147168)
-- Name: espace_protege_ref; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE espace_protege_ref (
    id integer NOT NULL,
    geom geometry(MultiPolygon,4326),
    id_local character varying(15),
    prn_asso character varying(15),
    code_r_enp character varying(5),
    nom_site character varying(254),
    date_crea date,
    modif_adm date,
    modif_geo date,
    url_fiche character varying(254),
    surf_off double precision,
    acte_deb character varying(50),
    acte_fin character varying(50),
    gest_site character varying(100),
    operateur character varying(50),
    precision_ character varying(2),
    src_geom character varying(100),
    src_annee character varying(4),
    marin character varying(1),
    p1_nature character varying(1),
    p2_culture character varying(1),
    p3_paysage character varying(1),
    p4_geologi character varying(1),
    p5_speleo character varying(1),
    p6_archeo character varying(1),
    p7_paleob character varying(1),
    p8_anthrop character varying(1),
    p9_science character varying(1),
    p10_public character varying(1),
    p11_dd character varying(1),
    p12_autre character varying(1),
    id_mnhn character varying(30),
    source_georef character varying(200),
    gest_site_2 character varying(255),
    id_inpn character varying(12)
);


ALTER TABLE espace_protege_ref OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 147174)
-- Name: groups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE groups (
    id integer NOT NULL,
    name character varying(128) NOT NULL,
    description character varying(100) NOT NULL,
    CONSTRAINT check_id CHECK ((id >= 0))
);


ALTER TABLE groups OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 147178)
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE groups_id_seq OWNER TO postgres;

--
-- TOC entry 4435 (class 0 OID 0)
-- Dependencies: 232
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE groups_id_seq OWNED BY groups.id;


--
-- TOC entry 233 (class 1259 OID 147180)
-- Name: login_attempts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE login_attempts (
    id integer NOT NULL,
    ip_address character varying(45),
    login character varying(100) NOT NULL,
    "time" integer,
    CONSTRAINT check_id CHECK ((id >= 0))
);


ALTER TABLE login_attempts OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 147184)
-- Name: login_attempts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE login_attempts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE login_attempts_id_seq OWNER TO postgres;

--
-- TOC entry 4436 (class 0 OID 0)
-- Dependencies: 234
-- Name: login_attempts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE login_attempts_id_seq OWNED BY login_attempts.id;


--
-- TOC entry 235 (class 1259 OID 147186)
-- Name: point_de_vue; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE point_de_vue (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    localisation character varying(255) DEFAULT NULL::character varying,
    description text,
    interne_ep boolean NOT NULL,
    observation_interne_ep boolean NOT NULL,
    id_ep integer,
    geom geometry(Point,4326) NOT NULL
);


ALTER TABLE point_de_vue OWNER TO postgres;

--
-- TOC entry 4437 (class 0 OID 0)
-- Dependencies: 235
-- Name: COLUMN point_de_vue.geom; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN point_de_vue.geom IS '(DC2Type:geometry)';


--
-- TOC entry 236 (class 1259 OID 147193)
-- Name: point_de_vue_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE point_de_vue_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE point_de_vue_id_seq OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 147195)
-- Name: qcm_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE qcm_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE qcm_id_seq OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 147197)
-- Name: qcm; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE qcm (
    id integer DEFAULT nextval('qcm_id_seq'::regclass) NOT NULL,
    question character varying(10) NOT NULL,
    label character varying(255) NOT NULL,
    description text,
    ordre_par_question integer,
    hierarchie character varying(255) DEFAULT NULL::character varying,
    rubrique character varying(100),
    page_dico integer,
    intitule_complement character varying(45)
);


ALTER TABLE qcm OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 147206)
-- Name: qestion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE qestion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE qestion_id_seq OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 147208)
-- Name: user_groups_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_groups_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_groups_seq OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 147210)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE users (
    id integer NOT NULL,
    ip_address character varying(45),
    username character varying(100),
    password character varying(255) NOT NULL,
    salt character varying(255),
    email character varying(254) NOT NULL,
    activation_code character varying(40),
    forgotten_password_code character varying(40),
    forgotten_password_time integer,
    remember_code character varying(40),
    created_on integer NOT NULL,
    last_login integer,
    active integer,
    first_name character varying(50),
    last_name character varying(50),
    company character varying(100),
    phone character varying(20),
    CONSTRAINT check_active CHECK ((active >= 0)),
    CONSTRAINT check_id CHECK ((id >= 0))
);


ALTER TABLE users OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 147218)
-- Name: users_groups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE users_groups (
    id integer NOT NULL,
    user_id integer NOT NULL,
    group_id integer NOT NULL,
    CONSTRAINT users_groups_check_group_id CHECK ((group_id >= 0)),
    CONSTRAINT users_groups_check_id CHECK ((id >= 0)),
    CONSTRAINT users_groups_check_user_id CHECK ((user_id >= 0))
);


ALTER TABLE users_groups OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 147224)
-- Name: users_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE users_groups_id_seq OWNER TO postgres;

--
-- TOC entry 4438 (class 0 OID 0)
-- Dependencies: 243
-- Name: users_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_groups_id_seq OWNED BY users_groups.id;


--
-- TOC entry 244 (class 1259 OID 147226)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE users_id_seq OWNER TO postgres;

--
-- TOC entry 4439 (class 0 OID 0)
-- Dependencies: 244
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 4189 (class 2604 OID 147228)
-- Name: commentaire id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY commentaire ALTER COLUMN id SET DEFAULT nextval('commentaire_id_seq'::regclass);


--
-- TOC entry 4190 (class 2604 OID 147229)
-- Name: commentaire_eg id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY commentaire_eg ALTER COLUMN id SET DEFAULT nextval('commentaire_eg_id_seq'::regclass);


--
-- TOC entry 4191 (class 2604 OID 147230)
-- Name: complement id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY complement ALTER COLUMN id SET DEFAULT nextval('element_supplementaire_id_seq'::regclass);


--
-- TOC entry 4192 (class 2604 OID 147231)
-- Name: complement_eg id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY complement_eg ALTER COLUMN id SET DEFAULT nextval('complement_eg_id_seq'::regclass);


--
-- TOC entry 4193 (class 2604 OID 147232)
-- Name: emprise_cartes_geol id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY emprise_cartes_geol ALTER COLUMN id SET DEFAULT nextval('emprise_cartes_geol_id_seq'::regclass);


--
-- TOC entry 4194 (class 2604 OID 147233)
-- Name: entite_geol id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entite_geol ALTER COLUMN id SET DEFAULT nextval('entite_geol_id_seq'::regclass);


--
-- TOC entry 4201 (class 2604 OID 147234)
-- Name: groups id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY groups ALTER COLUMN id SET DEFAULT nextval('groups_id_seq'::regclass);


--
-- TOC entry 4203 (class 2604 OID 147235)
-- Name: login_attempts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY login_attempts ALTER COLUMN id SET DEFAULT nextval('login_attempts_id_seq'::regclass);


--
-- TOC entry 4208 (class 2604 OID 147236)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 4211 (class 2604 OID 147237)
-- Name: users_groups id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_groups ALTER COLUMN id SET DEFAULT nextval('users_groups_id_seq'::regclass);


--
-- TOC entry 4216 (class 2606 OID 147858)
-- Name: ci_sessions ci_sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ci_sessions
    ADD CONSTRAINT ci_sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 4222 (class 2606 OID 147860)
-- Name: commentaire_eg commentaire_eg_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY commentaire_eg
    ADD CONSTRAINT commentaire_eg_pkey PRIMARY KEY (id);


--
-- TOC entry 4219 (class 2606 OID 147862)
-- Name: commentaire commentaire_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY commentaire
    ADD CONSTRAINT commentaire_pkey PRIMARY KEY (id);


--
-- TOC entry 4228 (class 2606 OID 147864)
-- Name: complement_eg complement_eg_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY complement_eg
    ADD CONSTRAINT complement_eg_pkey PRIMARY KEY (id);


--
-- TOC entry 4231 (class 2606 OID 147866)
-- Name: echelle_geol echelle_geol2_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY echelle_geol
    ADD CONSTRAINT echelle_geol2_pkey PRIMARY KEY (id);


--
-- TOC entry 4226 (class 2606 OID 147868)
-- Name: complement element_supplementaire_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY complement
    ADD CONSTRAINT element_supplementaire_pkey PRIMARY KEY (id);


--
-- TOC entry 4234 (class 2606 OID 147870)
-- Name: emprise_cartes_geol emprise_cartes_geol_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY emprise_cartes_geol
    ADD CONSTRAINT emprise_cartes_geol_pkey PRIMARY KEY (id);


--
-- TOC entry 4238 (class 2606 OID 147872)
-- Name: entite_geol entite_geol_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entite_geol
    ADD CONSTRAINT entite_geol_pkey PRIMARY KEY (id);


--
-- TOC entry 4246 (class 2606 OID 147874)
-- Name: espace_protege espace_protege_code_national_ep_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY espace_protege
    ADD CONSTRAINT espace_protege_code_national_ep_key UNIQUE (code_national_ep);


--
-- TOC entry 4248 (class 2606 OID 147876)
-- Name: espace_protege espace_protege_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY espace_protege
    ADD CONSTRAINT espace_protege_pkey PRIMARY KEY (id);


--
-- TOC entry 4251 (class 2606 OID 147878)
-- Name: espace_protege_qcm espace_protege_qcm_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY espace_protege_qcm
    ADD CONSTRAINT espace_protege_qcm_pkey PRIMARY KEY (espace_protege_id, qcm_id);


--
-- TOC entry 4255 (class 2606 OID 147880)
-- Name: espace_protege_ref espace_protege_ref_id_mnhn_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY espace_protege_ref
    ADD CONSTRAINT espace_protege_ref_id_mnhn_key UNIQUE (id_mnhn);


--
-- TOC entry 4257 (class 2606 OID 147882)
-- Name: espace_protege_ref espace_protege_ref_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY espace_protege_ref
    ADD CONSTRAINT espace_protege_ref_pkey PRIMARY KEY (id);


--
-- TOC entry 4261 (class 2606 OID 147884)
-- Name: groups groups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- TOC entry 4264 (class 2606 OID 147886)
-- Name: login_attempts login_attempts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY login_attempts
    ADD CONSTRAINT login_attempts_pkey PRIMARY KEY (id);


--
-- TOC entry 4244 (class 2606 OID 147888)
-- Name: entite_geol_qcm pk_entite_geol_qcm; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entite_geol_qcm
    ADD CONSTRAINT pk_entite_geol_qcm PRIMARY KEY (entite_geol_id, qcm_id);


--
-- TOC entry 4267 (class 2606 OID 147890)
-- Name: point_de_vue point_de_vue_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY point_de_vue
    ADD CONSTRAINT point_de_vue_pkey PRIMARY KEY (id);


--
-- TOC entry 4269 (class 2606 OID 147892)
-- Name: qcm qcm_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY qcm
    ADD CONSTRAINT qcm_pkey PRIMARY KEY (id);


--
-- TOC entry 4276 (class 2606 OID 147894)
-- Name: users_groups uc_users_groups; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_groups
    ADD CONSTRAINT uc_users_groups UNIQUE (user_id, group_id);


--
-- TOC entry 4278 (class 2606 OID 147896)
-- Name: users_groups users_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_groups
    ADD CONSTRAINT users_groups_pkey PRIMARY KEY (id);


--
-- TOC entry 4274 (class 2606 OID 147898)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4217 (class 1259 OID 147899)
-- Name: ci_sessions_timestamp; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ci_sessions_timestamp ON ci_sessions USING btree ("timestamp");


--
-- TOC entry 4223 (class 1259 OID 147900)
-- Name: fki_fk_comment_eg_eg; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX fki_fk_comment_eg_eg ON commentaire_eg USING btree (entite_geol_id);


--
-- TOC entry 4229 (class 1259 OID 147901)
-- Name: fki_fk_complement_eg_eg; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX fki_fk_complement_eg_eg ON complement_eg USING btree (entite_geol_id);


--
-- TOC entry 4239 (class 1259 OID 147902)
-- Name: fki_fk_entite_geol_espace_protege; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX fki_fk_entite_geol_espace_protege ON entite_geol USING btree (espace_protege_id);


--
-- TOC entry 4241 (class 1259 OID 147903)
-- Name: fki_fk_entite_geol_qcm_eg; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX fki_fk_entite_geol_qcm_eg ON entite_geol_qcm USING btree (entite_geol_id);


--
-- TOC entry 4242 (class 1259 OID 147904)
-- Name: fki_fk_entite_geol_qcm_qcm; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX fki_fk_entite_geol_qcm_qcm ON entite_geol_qcm USING btree (qcm_id);


--
-- TOC entry 4232 (class 1259 OID 148018)
-- Name: fki_fk_interne_echelle_geol; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX fki_fk_interne_echelle_geol ON echelle_geol USING btree (parent);


--
-- TOC entry 4252 (class 1259 OID 147905)
-- Name: idx_5b55921b98297b0e; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_5b55921b98297b0e ON espace_protege_qcm USING btree (espace_protege_id);


--
-- TOC entry 4253 (class 1259 OID 147906)
-- Name: idx_5b55921bff6241a6; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_5b55921bff6241a6 ON espace_protege_qcm USING btree (qcm_id);


--
-- TOC entry 4265 (class 1259 OID 147907)
-- Name: idx_b652f0dec92b0526; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_b652f0dec92b0526 ON point_de_vue USING btree (id_ep);


--
-- TOC entry 4224 (class 1259 OID 147908)
-- Name: idx_commentaire_rub_eg; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_commentaire_rub_eg ON commentaire_eg USING btree (entite_geol_id, rubrique);


--
-- TOC entry 4220 (class 1259 OID 147909)
-- Name: idx_commentaire_rub_ep; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_commentaire_rub_ep ON commentaire USING btree (espace_protege_id, rubrique);


--
-- TOC entry 4235 (class 1259 OID 147910)
-- Name: idx_emprises_cartes_geol_numero; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_emprises_cartes_geol_numero ON emprise_cartes_geol USING btree (numero);


--
-- TOC entry 4249 (class 1259 OID 147911)
-- Name: idx_ep_code_ep; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_ep_code_ep ON espace_protege USING btree (code_national_ep);


--
-- TOC entry 4258 (class 1259 OID 147912)
-- Name: idx_espace_protege_ref_id_mnhn; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_espace_protege_ref_id_mnhn ON espace_protege_ref USING btree (id_mnhn);


--
-- TOC entry 4262 (class 1259 OID 147913)
-- Name: idx_groups_name; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_groups_name ON groups USING btree (name);


--
-- TOC entry 4272 (class 1259 OID 147914)
-- Name: idx_users_email; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_users_email ON users USING btree (email);


--
-- TOC entry 4270 (class 1259 OID 147915)
-- Name: qcm_question_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX qcm_question_idx ON qcm USING btree (question);


--
-- TOC entry 4271 (class 1259 OID 147916)
-- Name: qcm_rubrique_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX qcm_rubrique_idx ON qcm USING btree (rubrique);


--
-- TOC entry 4240 (class 1259 OID 147917)
-- Name: sidx_eg_geom; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sidx_eg_geom ON entite_geol USING gist (geom);


--
-- TOC entry 4236 (class 1259 OID 147918)
-- Name: sidx_emprise_cartes_geol_geom; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sidx_emprise_cartes_geol_geom ON emprise_cartes_geol USING gist (geom);


--
-- TOC entry 4259 (class 1259 OID 147919)
-- Name: sidx_espace_protege_ref_geom; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sidx_espace_protege_ref_geom ON espace_protege_ref USING gist (geom);


--
-- TOC entry 4286 (class 2606 OID 147920)
-- Name: espace_protege_qcm fk_5b55921b98297b0e; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY espace_protege_qcm
    ADD CONSTRAINT fk_5b55921b98297b0e FOREIGN KEY (espace_protege_id) REFERENCES espace_protege(id) ON DELETE CASCADE;


--
-- TOC entry 4287 (class 2606 OID 147925)
-- Name: espace_protege_qcm fk_5b55921bff6241a6; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY espace_protege_qcm
    ADD CONSTRAINT fk_5b55921bff6241a6 FOREIGN KEY (qcm_id) REFERENCES qcm(id) ON DELETE CASCADE;


--
-- TOC entry 4288 (class 2606 OID 147930)
-- Name: point_de_vue fk_b652f0dec92b0526; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY point_de_vue
    ADD CONSTRAINT fk_b652f0dec92b0526 FOREIGN KEY (id_ep) REFERENCES espace_protege(id);


--
-- TOC entry 4279 (class 2606 OID 147935)
-- Name: commentaire_eg fk_comment_eg_eg; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY commentaire_eg
    ADD CONSTRAINT fk_comment_eg_eg FOREIGN KEY (entite_geol_id) REFERENCES entite_geol(id);


--
-- TOC entry 4280 (class 2606 OID 147940)
-- Name: complement_eg fk_complement_eg_eg; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY complement_eg
    ADD CONSTRAINT fk_complement_eg_eg FOREIGN KEY (entite_geol_id) REFERENCES entite_geol(id);


--
-- TOC entry 4282 (class 2606 OID 147945)
-- Name: entite_geol fk_entite_geol_espace_protege; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entite_geol
    ADD CONSTRAINT fk_entite_geol_espace_protege FOREIGN KEY (espace_protege_id) REFERENCES espace_protege(id);


--
-- TOC entry 4283 (class 2606 OID 147950)
-- Name: entite_geol_qcm fk_entite_geol_qcm_eg; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entite_geol_qcm
    ADD CONSTRAINT fk_entite_geol_qcm_eg FOREIGN KEY (entite_geol_id) REFERENCES entite_geol(id);


--
-- TOC entry 4284 (class 2606 OID 147955)
-- Name: entite_geol_qcm fk_entite_geol_qcm_qcm; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entite_geol_qcm
    ADD CONSTRAINT fk_entite_geol_qcm_qcm FOREIGN KEY (qcm_id) REFERENCES qcm(id);


--
-- TOC entry 4285 (class 2606 OID 147960)
-- Name: espace_protege fk_ep_ep_ref; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY espace_protege
    ADD CONSTRAINT fk_ep_ep_ref FOREIGN KEY (code_national_ep) REFERENCES espace_protege_ref(id_mnhn);


--
-- TOC entry 4281 (class 2606 OID 148013)
-- Name: echelle_geol fk_interne_echelle_geol; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY echelle_geol
    ADD CONSTRAINT fk_interne_echelle_geol FOREIGN KEY (parent) REFERENCES echelle_geol(id);


-- Completed on 2017-11-17 11:57:47

--
-- PostgreSQL database dump complete
--

