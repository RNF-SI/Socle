--
-- PostgreSQL database dump
--

-- Dumped from database version 10.0
-- Dumped by pg_dump version 11.3

-- Started on 2019-10-09 12:38:35

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 8 (class 2615 OID 3806035)
-- Name: infoterre; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA infoterre;


--
-- TOC entry 2 (class 3079 OID 451951)
-- Name: postgis; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA public;


--
-- TOC entry 4510 (class 0 OID 0)
-- Dependencies: 2
-- Name: EXTENSION postgis; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION postgis IS 'PostGIS geometry, geography, and raster spatial types and functions';


SET default_with_oids = false;

--
-- TOC entry 252 (class 1259 OID 3809293)
-- Name: echelle; Type: TABLE; Schema: infoterre; Owner: -
--

CREATE TABLE infoterre.echelle (
    id integer NOT NULL,
    label character varying(50) NOT NULL,
    parent character varying(50),
    parent_id integer,
    age_deb numeric,
    age_fin numeric,
    pix_min integer,
    pix_max integer
);


--
-- TOC entry 253 (class 1259 OID 3809356)
-- Name: echelle_id_seq; Type: SEQUENCE; Schema: infoterre; Owner: -
--

CREATE SEQUENCE infoterre.echelle_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4511 (class 0 OID 0)
-- Dependencies: 253
-- Name: echelle_id_seq; Type: SEQUENCE OWNED BY; Schema: infoterre; Owner: -
--

ALTER SEQUENCE infoterre.echelle_id_seq OWNED BY infoterre.echelle.id;


--
-- TOC entry 223 (class 1259 OID 453657)
-- Name: emprise_cartes_geol; Type: TABLE; Schema: infoterre; Owner: -
--

CREATE TABLE infoterre.emprise_cartes_geol (
    id integer NOT NULL,
    geom public.geometry(Polygon,4326) NOT NULL,
    nom character varying(150),
    numero integer
);


--
-- TOC entry 222 (class 1259 OID 453655)
-- Name: emprise_cartes_geol_id_seq; Type: SEQUENCE; Schema: infoterre; Owner: -
--

CREATE SEQUENCE infoterre.emprise_cartes_geol_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4512 (class 0 OID 0)
-- Dependencies: 222
-- Name: emprise_cartes_geol_id_seq; Type: SEQUENCE OWNED BY; Schema: infoterre; Owner: -
--

ALTER SEQUENCE infoterre.emprise_cartes_geol_id_seq OWNED BY infoterre.emprise_cartes_geol.id;


--
-- TOC entry 254 (class 1259 OID 3810524)
-- Name: s_fgeol; Type: TABLE; Schema: infoterre; Owner: -
--

CREATE TABLE infoterre.s_fgeol (
    ogc_fid integer NOT NULL,
    mi_prinx integer,
    code integer,
    code_leg integer,
    notation character varying(50),
    descr character varying(254),
    type_geol character varying(254),
    ap_locale character varying(254),
    type_ap character varying(254),
    geol_nat character varying(254),
    isopique character varying(254),
    lithotec character varying(254),
    emerge character varying(4),
    sys_deb character varying(50),
    sys_fin character varying(50),
    age_min numeric(6,2),
    age_max numeric(6,2),
    age_absolu numeric(6,2),
    toler_age numeric(5,2),
    tech_dat character varying(254),
    cat_dat character varying(254),
    age_com character varying(254),
    lithologie character varying(254),
    durete character varying(254),
    epaisseur character varying(254),
    environmt character varying(254),
    c_geodyn character varying(254),
    geochimie character varying(254),
    litho_com character varying(254),
    wkb_geometry public.geometry(Polygon,4326),
    age_deb_id integer,
    age_fin_id integer
);


--
-- TOC entry 257 (class 1259 OID 8332041)
-- Name: s_fgeol_agg; Type: TABLE; Schema: infoterre; Owner: -
--

CREATE TABLE infoterre.s_fgeol_agg (
    array_agg integer[],
    code integer,
    code_leg integer,
    notation character varying(50),
    descr character varying(254),
    type_geol character varying(254),
    ap_locale character varying(254),
    type_ap character varying(254),
    geol_nat character varying(254),
    isopique character varying(254),
    lithotec character varying(254),
    emerge character varying(4),
    sys_deb character varying(50),
    sys_fin character varying(50),
    age_min numeric(6,2),
    age_max numeric(6,2),
    age_absolu numeric(6,2),
    toler_age numeric(5,2),
    tech_dat character varying(254),
    cat_dat character varying(254),
    age_com character varying(254),
    lithologie character varying(254),
    durete character varying(254),
    epaisseur character varying(254),
    environmt character varying(254),
    c_geodyn character varying(254),
    geochimie character varying(254),
    litho_com character varying(254),
    geom public.geometry(MultiPolygon,4326),
    age_deb_id integer,
    age_fin_id integer,
    id integer NOT NULL
);


--
-- TOC entry 258 (class 1259 OID 8411285)
-- Name: s_fgeol_agg_id_seq; Type: SEQUENCE; Schema: infoterre; Owner: -
--

CREATE SEQUENCE infoterre.s_fgeol_agg_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4513 (class 0 OID 0)
-- Dependencies: 258
-- Name: s_fgeol_agg_id_seq; Type: SEQUENCE OWNED BY; Schema: infoterre; Owner: -
--

ALTER SEQUENCE infoterre.s_fgeol_agg_id_seq OWNED BY infoterre.s_fgeol_agg.id;


--
-- TOC entry 255 (class 1259 OID 3810530)
-- Name: s_fgeol_ogc_fid_seq; Type: SEQUENCE; Schema: infoterre; Owner: -
--

CREATE SEQUENCE infoterre.s_fgeol_ogc_fid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4514 (class 0 OID 0)
-- Dependencies: 255
-- Name: s_fgeol_ogc_fid_seq; Type: SEQUENCE OWNED BY; Schema: infoterre; Owner: -
--

ALTER SEQUENCE infoterre.s_fgeol_ogc_fid_seq OWNED BY infoterre.s_fgeol.ogc_fid;


--
-- TOC entry 246 (class 1259 OID 457364)
-- Name: affleurement; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.affleurement (
    id integer NOT NULL,
    nom character varying(150) NOT NULL,
    description text,
    geom public.geometry(Point,4326),
    eg_id integer,
    type character varying(50) DEFAULT 'affleurement'::character varying
);


--
-- TOC entry 245 (class 1259 OID 457362)
-- Name: affleurement_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.affleurement_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4515 (class 0 OID 0)
-- Dependencies: 245
-- Name: affleurement_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.affleurement_id_seq OWNED BY public.affleurement.id;


--
-- TOC entry 216 (class 1259 OID 453619)
-- Name: commentaire; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.commentaire (
    id integer NOT NULL,
    rubrique character varying(50),
    commentaire text,
    site_id integer NOT NULL
);


--
-- TOC entry 214 (class 1259 OID 453608)
-- Name: commentaire_eg; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.commentaire_eg (
    id integer NOT NULL,
    rubrique character varying(50),
    commentaire text,
    entite_geol_id integer NOT NULL
);


--
-- TOC entry 213 (class 1259 OID 453606)
-- Name: commentaire_eg_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.commentaire_eg_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4516 (class 0 OID 0)
-- Dependencies: 213
-- Name: commentaire_eg_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.commentaire_eg_id_seq OWNED BY public.commentaire_eg.id;


--
-- TOC entry 215 (class 1259 OID 453617)
-- Name: commentaire_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.commentaire_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4517 (class 0 OID 0)
-- Dependencies: 215
-- Name: commentaire_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.commentaire_id_seq OWNED BY public.commentaire.id;


--
-- TOC entry 218 (class 1259 OID 453630)
-- Name: complement_eg; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.complement_eg (
    id integer NOT NULL,
    question character varying(15),
    elements text,
    entite_geol_id integer NOT NULL
);


--
-- TOC entry 217 (class 1259 OID 453628)
-- Name: complement_eg_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.complement_eg_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4518 (class 0 OID 0)
-- Dependencies: 217
-- Name: complement_eg_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.complement_eg_id_seq OWNED BY public.complement_eg.id;


--
-- TOC entry 220 (class 1259 OID 453641)
-- Name: complement_site; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.complement_site (
    id integer NOT NULL,
    site_id integer NOT NULL,
    question character varying(15),
    elements text
);


--
-- TOC entry 219 (class 1259 OID 453639)
-- Name: complement_site_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.complement_site_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4519 (class 0 OID 0)
-- Dependencies: 219
-- Name: complement_site_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.complement_site_id_seq OWNED BY public.complement_site.id;


--
-- TOC entry 221 (class 1259 OID 453650)
-- Name: echelle_geol; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.echelle_geol (
    id integer NOT NULL,
    label character varying(150),
    parent integer,
    date_deb numeric,
    date_fin numeric
);


--
-- TOC entry 225 (class 1259 OID 453668)
-- Name: entite_geol; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.entite_geol (
    id integer NOT NULL,
    intitule character varying(255) NOT NULL,
    code character varying(10),
    quantite_affleurements character varying(50),
    affleurements_accessibles boolean,
    permeabilite character varying(50),
    presence_aquifere boolean,
    niveau_sources boolean,
    complements text,
    geom public.geometry(MultiPoint,4326),
    ere_geol_id integer,
    site_id integer NOT NULL,
    last_modified timestamp with time zone DEFAULT now() NOT NULL,
    modified_by_userid integer,
    nom_carte character varying(254),
    s_fgeol_id integer
);


--
-- TOC entry 224 (class 1259 OID 453666)
-- Name: entite_geol_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.entite_geol_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4520 (class 0 OID 0)
-- Dependencies: 224
-- Name: entite_geol_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.entite_geol_id_seq OWNED BY public.entite_geol.id;


--
-- TOC entry 227 (class 1259 OID 453679)
-- Name: entite_geol_qcm; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.entite_geol_qcm (
    id integer NOT NULL,
    info_complement character varying(255),
    remarquable boolean,
    entite_geol_id integer NOT NULL,
    qcm_id integer NOT NULL,
    interet_scientifique boolean,
    interet_pedagogique boolean,
    interet_esthetique boolean,
    interet_historique boolean,
    remarquable_info text,
    geom jsonb
);


--
-- TOC entry 226 (class 1259 OID 453677)
-- Name: entite_geol_qcm_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.entite_geol_qcm_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4521 (class 0 OID 0)
-- Dependencies: 226
-- Name: entite_geol_qcm_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.entite_geol_qcm_id_seq OWNED BY public.entite_geol_qcm.id;


--
-- TOC entry 229 (class 1259 OID 453687)
-- Name: espace_protege; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.espace_protege (
    id integer NOT NULL,
    code_national_ep character varying(50) NOT NULL,
    nom character varying(150) NOT NULL,
    type character varying(255) NOT NULL,
    sous_type character varying(255),
    numero integer,
    surface double precision,
    geom public.geometry(MultiPolygon,4326) NOT NULL,
    monosite boolean DEFAULT true NOT NULL,
    group_id integer
);


--
-- TOC entry 228 (class 1259 OID 453685)
-- Name: espace_protege_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.espace_protege_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4522 (class 0 OID 0)
-- Dependencies: 228
-- Name: espace_protege_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.espace_protege_id_seq OWNED BY public.espace_protege.id;


--
-- TOC entry 244 (class 1259 OID 456787)
-- Name: espace_protege_ref; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.espace_protege_ref (
    id integer NOT NULL,
    geom public.geometry(MultiPolygon,4326),
    id_local character varying(15),
    code_r_enp character varying(5),
    nom_site character varying(254),
    date_crea date,
    modif_adm date,
    modif_geo date,
    surf_off double precision,
    acte_deb character varying(50),
    acte_fin character varying(50),
    gest_site character varying(100),
    operateur character varying(50),
    src_geom character varying(100),
    src_annee character varying(4),
    marin character varying(1),
    id_mnhn character varying(30),
    gest_site_2 character varying(255),
    id_rnf integer,
    jonction_nom character varying(12),
    chemin_acte character varying(255),
    outremer boolean
);


--
-- TOC entry 239 (class 1259 OID 456758)
-- Name: groups; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.groups (
    id integer NOT NULL,
    name character varying(20) NOT NULL,
    description character varying(100) NOT NULL,
    CONSTRAINT check_id CHECK ((id >= 0))
);


--
-- TOC entry 238 (class 1259 OID 456756)
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.groups_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4523 (class 0 OID 0)
-- Dependencies: 238
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.groups_id_seq OWNED BY public.groups.id;


--
-- TOC entry 243 (class 1259 OID 456780)
-- Name: login_attempts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.login_attempts (
    id integer NOT NULL,
    ip_address character varying(45),
    login character varying(100) NOT NULL,
    "time" integer,
    CONSTRAINT check_id CHECK ((id >= 0))
);


--
-- TOC entry 242 (class 1259 OID 456778)
-- Name: login_attempts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.login_attempts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4524 (class 0 OID 0)
-- Dependencies: 242
-- Name: login_attempts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.login_attempts_id_seq OWNED BY public.login_attempts.id;


--
-- TOC entry 251 (class 1259 OID 507865)
-- Name: ontology_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ontology_id_seq
    START WITH 1017
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 250 (class 1259 OID 507833)
-- Name: ontology; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ontology (
    id integer DEFAULT nextval('public.ontology_id_seq'::regclass) NOT NULL,
    label character varying(255),
    definition text,
    id_parent integer,
    description text,
    checkable boolean DEFAULT false NOT NULL,
    nullying boolean DEFAULT false NOT NULL,
    class character varying(124),
    attached_to character varying(124)
);


--
-- TOC entry 4525 (class 0 OID 0)
-- Dependencies: 250
-- Name: COLUMN ontology.nullying; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.ontology.nullying IS 'permet de désactiver tous les frères et soeurs';


--
-- TOC entry 248 (class 1259 OID 457389)
-- Name: photo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.photo (
    id integer NOT NULL,
    url character varying(255) NOT NULL,
    description text,
    site_id integer,
    eg_id integer,
    mimetype character varying(50)
);


--
-- TOC entry 247 (class 1259 OID 457387)
-- Name: photo_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.photo_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4526 (class 0 OID 0)
-- Dependencies: 247
-- Name: photo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.photo_id_seq OWNED BY public.photo.id;


--
-- TOC entry 231 (class 1259 OID 453700)
-- Name: qcm; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.qcm (
    id integer NOT NULL,
    question character varying(10) NOT NULL,
    label character varying(255) NOT NULL,
    description text,
    ordre_par_question integer,
    hierarchie character varying(255),
    rubrique character varying(100),
    page_dico integer,
    intitule_complement character varying(45),
    parent integer
);


--
-- TOC entry 230 (class 1259 OID 453698)
-- Name: qcm_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.qcm_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4527 (class 0 OID 0)
-- Dependencies: 230
-- Name: qcm_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.qcm_id_seq OWNED BY public.qcm.id;


--
-- TOC entry 249 (class 1259 OID 507825)
-- Name: rubrique; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.rubrique (
    id character varying(100) NOT NULL,
    description text,
    obligatoire boolean NOT NULL,
    niveau character varying(50)
);


--
-- TOC entry 233 (class 1259 OID 453711)
-- Name: site; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.site (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    altitude_max double precision,
    altitude_min double precision,
    bassin_hydro_general character varying(255),
    bassin_hydro_rapproche character varying(255),
    observations_in_situ text,
    liste_docs_geol text,
    autres_cartes_geol text,
    statut_validation character varying(20),
    geom public.geometry(MultiPolygon,4326),
    ep_id integer NOT NULL,
    last_modified timestamp with time zone DEFAULT now() NOT NULL,
    modified_by_userid integer
);


--
-- TOC entry 232 (class 1259 OID 453709)
-- Name: site_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.site_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4528 (class 0 OID 0)
-- Dependencies: 232
-- Name: site_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.site_id_seq OWNED BY public.site.id;


--
-- TOC entry 256 (class 1259 OID 8332015)
-- Name: site_ontology; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.site_ontology (
    info_complement character varying(255),
    remarquable boolean,
    qcm_id integer NOT NULL,
    site_id integer NOT NULL,
    interet_scientifique boolean,
    interet_pedagogique boolean,
    interet_esthetique boolean,
    interet_historique boolean,
    remarquable_info text
);


--
-- TOC entry 235 (class 1259 OID 453722)
-- Name: site_qcm; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.site_qcm (
    id integer NOT NULL,
    info_complement character varying(255),
    remarquable boolean,
    qcm_id integer NOT NULL,
    site_id integer NOT NULL,
    interet_scientifique boolean,
    interet_pedagogique boolean,
    interet_esthetique boolean,
    interet_historique boolean,
    remarquable_info text,
    geom jsonb
);


--
-- TOC entry 234 (class 1259 OID 453720)
-- Name: site_qcm_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.site_qcm_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4529 (class 0 OID 0)
-- Dependencies: 234
-- Name: site_qcm_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.site_qcm_id_seq OWNED BY public.site_qcm.id;


--
-- TOC entry 237 (class 1259 OID 456745)
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
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


--
-- TOC entry 241 (class 1259 OID 456767)
-- Name: users_groups; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users_groups (
    id integer NOT NULL,
    user_id integer NOT NULL,
    group_id integer NOT NULL,
    CONSTRAINT users_groups_check_group_id CHECK ((group_id >= 0)),
    CONSTRAINT users_groups_check_id CHECK ((id >= 0)),
    CONSTRAINT users_groups_check_user_id CHECK ((user_id >= 0))
);


--
-- TOC entry 240 (class 1259 OID 456765)
-- Name: users_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_groups_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4530 (class 0 OID 0)
-- Dependencies: 240
-- Name: users_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_groups_id_seq OWNED BY public.users_groups.id;


--
-- TOC entry 236 (class 1259 OID 456743)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4531 (class 0 OID 0)
-- Dependencies: 236
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4266 (class 2604 OID 3809386)
-- Name: echelle id; Type: DEFAULT; Schema: infoterre; Owner: -
--

ALTER TABLE ONLY infoterre.echelle ALTER COLUMN id SET DEFAULT nextval('infoterre.echelle_id_seq'::regclass);


--
-- TOC entry 4239 (class 2604 OID 453660)
-- Name: emprise_cartes_geol id; Type: DEFAULT; Schema: infoterre; Owner: -
--

ALTER TABLE ONLY infoterre.emprise_cartes_geol ALTER COLUMN id SET DEFAULT nextval('infoterre.emprise_cartes_geol_id_seq'::regclass);


--
-- TOC entry 4267 (class 2604 OID 3810532)
-- Name: s_fgeol ogc_fid; Type: DEFAULT; Schema: infoterre; Owner: -
--

ALTER TABLE ONLY infoterre.s_fgeol ALTER COLUMN ogc_fid SET DEFAULT nextval('infoterre.s_fgeol_ogc_fid_seq'::regclass);


--
-- TOC entry 4268 (class 2604 OID 8411287)
-- Name: s_fgeol_agg id; Type: DEFAULT; Schema: infoterre; Owner: -
--

ALTER TABLE ONLY infoterre.s_fgeol_agg ALTER COLUMN id SET DEFAULT nextval('infoterre.s_fgeol_agg_id_seq'::regclass);


--
-- TOC entry 4260 (class 2604 OID 457367)
-- Name: affleurement id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.affleurement ALTER COLUMN id SET DEFAULT nextval('public.affleurement_id_seq'::regclass);


--
-- TOC entry 4236 (class 2604 OID 453622)
-- Name: commentaire id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commentaire ALTER COLUMN id SET DEFAULT nextval('public.commentaire_id_seq'::regclass);


--
-- TOC entry 4235 (class 2604 OID 453611)
-- Name: commentaire_eg id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commentaire_eg ALTER COLUMN id SET DEFAULT nextval('public.commentaire_eg_id_seq'::regclass);


--
-- TOC entry 4237 (class 2604 OID 453633)
-- Name: complement_eg id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.complement_eg ALTER COLUMN id SET DEFAULT nextval('public.complement_eg_id_seq'::regclass);


--
-- TOC entry 4238 (class 2604 OID 453644)
-- Name: complement_site id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.complement_site ALTER COLUMN id SET DEFAULT nextval('public.complement_site_id_seq'::regclass);


--
-- TOC entry 4240 (class 2604 OID 453671)
-- Name: entite_geol id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.entite_geol ALTER COLUMN id SET DEFAULT nextval('public.entite_geol_id_seq'::regclass);


--
-- TOC entry 4242 (class 2604 OID 453682)
-- Name: entite_geol_qcm id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.entite_geol_qcm ALTER COLUMN id SET DEFAULT nextval('public.entite_geol_qcm_id_seq'::regclass);


--
-- TOC entry 4243 (class 2604 OID 453690)
-- Name: espace_protege id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.espace_protege ALTER COLUMN id SET DEFAULT nextval('public.espace_protege_id_seq'::regclass);


--
-- TOC entry 4252 (class 2604 OID 456761)
-- Name: groups id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.groups ALTER COLUMN id SET DEFAULT nextval('public.groups_id_seq'::regclass);


--
-- TOC entry 4258 (class 2604 OID 456783)
-- Name: login_attempts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.login_attempts ALTER COLUMN id SET DEFAULT nextval('public.login_attempts_id_seq'::regclass);


--
-- TOC entry 4262 (class 2604 OID 457392)
-- Name: photo id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.photo ALTER COLUMN id SET DEFAULT nextval('public.photo_id_seq'::regclass);


--
-- TOC entry 4245 (class 2604 OID 453703)
-- Name: qcm id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.qcm ALTER COLUMN id SET DEFAULT nextval('public.qcm_id_seq'::regclass);


--
-- TOC entry 4246 (class 2604 OID 453714)
-- Name: site id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.site ALTER COLUMN id SET DEFAULT nextval('public.site_id_seq'::regclass);


--
-- TOC entry 4248 (class 2604 OID 453725)
-- Name: site_qcm id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.site_qcm ALTER COLUMN id SET DEFAULT nextval('public.site_qcm_id_seq'::regclass);


--
-- TOC entry 4249 (class 2604 OID 456748)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 4254 (class 2604 OID 456770)
-- Name: users_groups id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users_groups ALTER COLUMN id SET DEFAULT nextval('public.users_groups_id_seq'::regclass);


--
-- TOC entry 4345 (class 2606 OID 3809401)
-- Name: echelle echelle_pkey; Type: CONSTRAINT; Schema: infoterre; Owner: -
--

ALTER TABLE ONLY infoterre.echelle
    ADD CONSTRAINT echelle_pkey PRIMARY KEY (id);


--
-- TOC entry 4287 (class 2606 OID 453665)
-- Name: emprise_cartes_geol emprise_cartes_geol_pkey; Type: CONSTRAINT; Schema: infoterre; Owner: -
--

ALTER TABLE ONLY infoterre.emprise_cartes_geol
    ADD CONSTRAINT emprise_cartes_geol_pkey PRIMARY KEY (id);


--
-- TOC entry 4359 (class 2606 OID 8490532)
-- Name: s_fgeol_agg s_fgeol_agg_pkey; Type: CONSTRAINT; Schema: infoterre; Owner: -
--

ALTER TABLE ONLY infoterre.s_fgeol_agg
    ADD CONSTRAINT s_fgeol_agg_pkey PRIMARY KEY (id);


--
-- TOC entry 4351 (class 2606 OID 4773708)
-- Name: s_fgeol s_fgeol_pkey; Type: CONSTRAINT; Schema: infoterre; Owner: -
--

ALTER TABLE ONLY infoterre.s_fgeol
    ADD CONSTRAINT s_fgeol_pkey PRIMARY KEY (ogc_fid);


--
-- TOC entry 4349 (class 2606 OID 3809411)
-- Name: echelle unique_echelle_label; Type: CONSTRAINT; Schema: infoterre; Owner: -
--

ALTER TABLE ONLY infoterre.echelle
    ADD CONSTRAINT unique_echelle_label UNIQUE (label);


--
-- TOC entry 4332 (class 2606 OID 457372)
-- Name: affleurement affleurement_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.affleurement
    ADD CONSTRAINT affleurement_pkey PRIMARY KEY (id);


--
-- TOC entry 4273 (class 2606 OID 453616)
-- Name: commentaire_eg commentaire_eg_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commentaire_eg
    ADD CONSTRAINT commentaire_eg_pkey PRIMARY KEY (id);


--
-- TOC entry 4275 (class 2606 OID 453627)
-- Name: commentaire commentaire_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commentaire
    ADD CONSTRAINT commentaire_pkey PRIMARY KEY (id);


--
-- TOC entry 4279 (class 2606 OID 453638)
-- Name: complement_eg complement_eg_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.complement_eg
    ADD CONSTRAINT complement_eg_pkey PRIMARY KEY (id);


--
-- TOC entry 4281 (class 2606 OID 453649)
-- Name: complement_site complement_site_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.complement_site
    ADD CONSTRAINT complement_site_pkey PRIMARY KEY (id);


--
-- TOC entry 4284 (class 2606 OID 453654)
-- Name: echelle_geol echelle_geol_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.echelle_geol
    ADD CONSTRAINT echelle_geol_pkey PRIMARY KEY (id);


--
-- TOC entry 4292 (class 2606 OID 453676)
-- Name: entite_geol entite_geol_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.entite_geol
    ADD CONSTRAINT entite_geol_pkey PRIMARY KEY (id);


--
-- TOC entry 4295 (class 2606 OID 453731)
-- Name: entite_geol_qcm entite_geol_qcm_entite_geol_id_qcm_id_0ff82bff_uniq; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.entite_geol_qcm
    ADD CONSTRAINT entite_geol_qcm_entite_geol_id_qcm_id_0ff82bff_uniq UNIQUE (entite_geol_id, qcm_id);


--
-- TOC entry 4297 (class 2606 OID 453684)
-- Name: entite_geol_qcm entite_geol_qcm_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.entite_geol_qcm
    ADD CONSTRAINT entite_geol_qcm_pkey PRIMARY KEY (id);


--
-- TOC entry 4301 (class 2606 OID 453697)
-- Name: espace_protege espace_protege_code_national_ep_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.espace_protege
    ADD CONSTRAINT espace_protege_code_national_ep_key UNIQUE (code_national_ep);


--
-- TOC entry 4304 (class 2606 OID 453695)
-- Name: espace_protege espace_protege_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.espace_protege
    ADD CONSTRAINT espace_protege_pkey PRIMARY KEY (id);


--
-- TOC entry 4328 (class 2606 OID 456791)
-- Name: espace_protege_ref espace_protege_ref_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.espace_protege_ref
    ADD CONSTRAINT espace_protege_ref_pkey PRIMARY KEY (id);


--
-- TOC entry 4320 (class 2606 OID 456764)
-- Name: groups groups_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- TOC entry 4326 (class 2606 OID 456786)
-- Name: login_attempts login_attempts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.login_attempts
    ADD CONSTRAINT login_attempts_pkey PRIMARY KEY (id);


--
-- TOC entry 4343 (class 2606 OID 507856)
-- Name: ontology ontology_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ontology
    ADD CONSTRAINT ontology_pkey PRIMARY KEY (id);


--
-- TOC entry 4338 (class 2606 OID 457397)
-- Name: photo photo_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.photo
    ADD CONSTRAINT photo_pkey PRIMARY KEY (id);


--
-- TOC entry 4314 (class 2606 OID 507869)
-- Name: site_qcm pk_site_qcm; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.site_qcm
    ADD CONSTRAINT pk_site_qcm PRIMARY KEY (qcm_id, site_id);


--
-- TOC entry 4308 (class 2606 OID 453708)
-- Name: qcm qcm_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.qcm
    ADD CONSTRAINT qcm_pkey PRIMARY KEY (id);


--
-- TOC entry 4340 (class 2606 OID 507832)
-- Name: rubrique rubrique_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rubrique
    ADD CONSTRAINT rubrique_pkey PRIMARY KEY (id);


--
-- TOC entry 4356 (class 2606 OID 8332022)
-- Name: site_ontology site_ontology_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.site_ontology
    ADD CONSTRAINT site_ontology_pkey PRIMARY KEY (qcm_id, site_id);


--
-- TOC entry 4312 (class 2606 OID 453719)
-- Name: site site_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.site
    ADD CONSTRAINT site_pkey PRIMARY KEY (id);


--
-- TOC entry 4322 (class 2606 OID 456777)
-- Name: users_groups uc_users_groups; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users_groups
    ADD CONSTRAINT uc_users_groups UNIQUE (user_id, group_id);


--
-- TOC entry 4324 (class 2606 OID 456775)
-- Name: users_groups users_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users_groups
    ADD CONSTRAINT users_groups_pkey PRIMARY KEY (id);


--
-- TOC entry 4318 (class 2606 OID 456755)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4285 (class 1259 OID 453738)
-- Name: emprise_cartes_geol_geom_id; Type: INDEX; Schema: infoterre; Owner: -
--

CREATE INDEX emprise_cartes_geol_geom_id ON infoterre.emprise_cartes_geol USING gist (geom);


--
-- TOC entry 4346 (class 1259 OID 3809412)
-- Name: fki_fk_echelle_auto; Type: INDEX; Schema: infoterre; Owner: -
--

CREATE INDEX fki_fk_echelle_auto ON infoterre.echelle USING btree (parent_id);


--
-- TOC entry 4347 (class 1259 OID 3809416)
-- Name: idx_echelle_label; Type: INDEX; Schema: infoterre; Owner: -
--

CREATE INDEX idx_echelle_label ON infoterre.echelle USING btree (label);


--
-- TOC entry 4357 (class 1259 OID 8490550)
-- Name: idx_s_f_geol_agg_geom; Type: INDEX; Schema: infoterre; Owner: -
--

CREATE INDEX idx_s_f_geol_agg_geom ON infoterre.s_fgeol_agg USING gist (geom);


--
-- TOC entry 4352 (class 1259 OID 4773710)
-- Name: s_fgeol_wkb_geometry_geom_idx; Type: INDEX; Schema: infoterre; Owner: -
--

CREATE INDEX s_fgeol_wkb_geometry_geom_idx ON infoterre.s_fgeol USING gist (wkb_geometry);


--
-- TOC entry 4271 (class 1259 OID 453797)
-- Name: commentaire_eg_entite_geol_id_61a507ba; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX commentaire_eg_entite_geol_id_61a507ba ON public.commentaire_eg USING btree (entite_geol_id);


--
-- TOC entry 4276 (class 1259 OID 453791)
-- Name: commentaire_site_id_0219b6c5; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX commentaire_site_id_0219b6c5 ON public.commentaire USING btree (site_id);


--
-- TOC entry 4277 (class 1259 OID 453785)
-- Name: complement_eg_entite_geol_id_41b9182f; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX complement_eg_entite_geol_id_41b9182f ON public.complement_eg USING btree (entite_geol_id);


--
-- TOC entry 4282 (class 1259 OID 453737)
-- Name: echelle_geol_parent_4a7d332d; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX echelle_geol_parent_4a7d332d ON public.echelle_geol USING btree (parent);


--
-- TOC entry 4288 (class 1259 OID 453745)
-- Name: entite_geol_ere_geol_id_c9a3cb0f; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX entite_geol_ere_geol_id_c9a3cb0f ON public.entite_geol USING btree (ere_geol_id);


--
-- TOC entry 4289 (class 1259 OID 453779)
-- Name: entite_geol_espace_protege_id_952f40c7; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX entite_geol_espace_protege_id_952f40c7 ON public.entite_geol USING btree (site_id);


--
-- TOC entry 4290 (class 1259 OID 457340)
-- Name: entite_geol_geom_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX entite_geol_geom_id ON public.entite_geol USING gist (geom);


--
-- TOC entry 4293 (class 1259 OID 453751)
-- Name: entite_geol_qcm_entite_geol_id_4a12bcfb; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX entite_geol_qcm_entite_geol_id_4a12bcfb ON public.entite_geol_qcm USING btree (entite_geol_id);


--
-- TOC entry 4298 (class 1259 OID 453773)
-- Name: entite_geol_qcm_qcm_id_174eb45a; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX entite_geol_qcm_qcm_id_174eb45a ON public.entite_geol_qcm USING btree (qcm_id);


--
-- TOC entry 4299 (class 1259 OID 453752)
-- Name: espace_protege_code_national_ep_55230d25_like; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX espace_protege_code_national_ep_55230d25_like ON public.espace_protege USING btree (code_national_ep varchar_pattern_ops);


--
-- TOC entry 4302 (class 1259 OID 453753)
-- Name: espace_protege_geom_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX espace_protege_geom_id ON public.espace_protege USING gist (geom);


--
-- TOC entry 4341 (class 1259 OID 507862)
-- Name: fki_fk_ontology_parent; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fki_fk_ontology_parent ON public.ontology USING btree (id_parent);


--
-- TOC entry 4333 (class 1259 OID 457386)
-- Name: idx_affl_id_eg; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_affl_id_eg ON public.affleurement USING btree (eg_id);


--
-- TOC entry 4329 (class 1259 OID 457299)
-- Name: idx_ep_ref_id_mnhn; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_ep_ref_id_mnhn ON public.espace_protege_ref USING btree (id_mnhn);


--
-- TOC entry 4334 (class 1259 OID 457867)
-- Name: idx_fk_photo_eg; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_fk_photo_eg ON public.photo USING btree (eg_id);


--
-- TOC entry 4335 (class 1259 OID 457493)
-- Name: idx_fk_photo_site; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_fk_photo_site ON public.photo USING btree (site_id);


--
-- TOC entry 4353 (class 1259 OID 8332028)
-- Name: idx_fk_site_ontology_ontology; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_fk_site_ontology_ontology ON public.site_ontology USING btree (qcm_id);


--
-- TOC entry 4354 (class 1259 OID 8332034)
-- Name: idx_fk_site_ontology_site; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_fk_site_ontology_site ON public.site_ontology USING btree (site_id);


--
-- TOC entry 4336 (class 1259 OID 457868)
-- Name: idx_photo_mimetype; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_photo_mimetype ON public.photo USING btree (mimetype);


--
-- TOC entry 4305 (class 1259 OID 497829)
-- Name: idx_qcm_question; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_qcm_question ON public.qcm USING btree (question);


--
-- TOC entry 4306 (class 1259 OID 8569810)
-- Name: idx_qcm_rubrique; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_qcm_rubrique ON public.qcm USING btree (rubrique);


--
-- TOC entry 4330 (class 1259 OID 457296)
-- Name: sidx_espace_protege_ref_geog; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sidx_espace_protege_ref_geog ON public.espace_protege_ref USING gist (geom);


--
-- TOC entry 4309 (class 1259 OID 453760)
-- Name: site_ep_id_312a3358; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX site_ep_id_312a3358 ON public.site USING btree (ep_id);


--
-- TOC entry 4310 (class 1259 OID 457321)
-- Name: site_geom_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX site_geom_id ON public.site USING gist (geom);


--
-- TOC entry 4315 (class 1259 OID 453771)
-- Name: site_qcm_qcm_id_a7bdb38f; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX site_qcm_qcm_id_a7bdb38f ON public.site_qcm USING btree (qcm_id);


--
-- TOC entry 4316 (class 1259 OID 453772)
-- Name: site_qcm_site_id_c3752863; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX site_qcm_site_id_c3752863 ON public.site_qcm USING btree (site_id);


--
-- TOC entry 4374 (class 2606 OID 3809417)
-- Name: echelle fk_echelle_auto; Type: FK CONSTRAINT; Schema: infoterre; Owner: -
--

ALTER TABLE ONLY infoterre.echelle
    ADD CONSTRAINT fk_echelle_auto FOREIGN KEY (parent_id) REFERENCES infoterre.echelle(id);


--
-- TOC entry 4370 (class 2606 OID 457381)
-- Name: affleurement affleurement_id_eg_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.affleurement
    ADD CONSTRAINT affleurement_id_eg_fkey FOREIGN KEY (eg_id) REFERENCES public.entite_geol(id);


--
-- TOC entry 4360 (class 2606 OID 453798)
-- Name: commentaire_eg commentaire_eg_entite_geol_id_61a507ba_fk_entite_geol_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commentaire_eg
    ADD CONSTRAINT commentaire_eg_entite_geol_id_61a507ba_fk_entite_geol_id FOREIGN KEY (entite_geol_id) REFERENCES public.entite_geol(id) DEFERRABLE INITIALLY DEFERRED;


--
-- TOC entry 4361 (class 2606 OID 457316)
-- Name: commentaire commentaire_site_id_0219b6c5_fk_site_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.commentaire
    ADD CONSTRAINT commentaire_site_id_0219b6c5_fk_site_id FOREIGN KEY (site_id) REFERENCES public.site(id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- TOC entry 4362 (class 2606 OID 453786)
-- Name: complement_eg complement_eg_entite_geol_id_41b9182f_fk_entite_geol_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.complement_eg
    ADD CONSTRAINT complement_eg_entite_geol_id_41b9182f_fk_entite_geol_id FOREIGN KEY (entite_geol_id) REFERENCES public.entite_geol(id) DEFERRABLE INITIALLY DEFERRED;


--
-- TOC entry 4363 (class 2606 OID 453732)
-- Name: echelle_geol echelle_geol_parent_4a7d332d_fk_echelle_geol_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.echelle_geol
    ADD CONSTRAINT echelle_geol_parent_4a7d332d_fk_echelle_geol_id FOREIGN KEY (parent) REFERENCES public.echelle_geol(id) DEFERRABLE INITIALLY DEFERRED;


--
-- TOC entry 4364 (class 2606 OID 453739)
-- Name: entite_geol entite_geol_ere_geol_id_c9a3cb0f_fk_echelle_geol_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.entite_geol
    ADD CONSTRAINT entite_geol_ere_geol_id_c9a3cb0f_fk_echelle_geol_id FOREIGN KEY (ere_geol_id) REFERENCES public.echelle_geol(id) DEFERRABLE INITIALLY DEFERRED;


--
-- TOC entry 4365 (class 2606 OID 457351)
-- Name: entite_geol entite_geol_espace_protege_id_952f40c7_fk_site_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.entite_geol
    ADD CONSTRAINT entite_geol_espace_protege_id_952f40c7_fk_site_id FOREIGN KEY (site_id) REFERENCES public.site(id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- TOC entry 4366 (class 2606 OID 453746)
-- Name: entite_geol_qcm entite_geol_qcm_entite_geol_id_4a12bcfb_fk_entite_geol_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.entite_geol_qcm
    ADD CONSTRAINT entite_geol_qcm_entite_geol_id_4a12bcfb_fk_entite_geol_id FOREIGN KEY (entite_geol_id) REFERENCES public.entite_geol(id) DEFERRABLE INITIALLY DEFERRED;


--
-- TOC entry 4367 (class 2606 OID 453774)
-- Name: entite_geol_qcm entite_geol_qcm_qcm_id_174eb45a_fk_qcm_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.entite_geol_qcm
    ADD CONSTRAINT entite_geol_qcm_qcm_id_174eb45a_fk_qcm_id FOREIGN KEY (qcm_id) REFERENCES public.qcm(id) DEFERRABLE INITIALLY DEFERRED;


--
-- TOC entry 4373 (class 2606 OID 507857)
-- Name: ontology fk_ontology_parent; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ontology
    ADD CONSTRAINT fk_ontology_parent FOREIGN KEY (id_parent) REFERENCES public.ontology(id);


--
-- TOC entry 4372 (class 2606 OID 457862)
-- Name: photo photo_eg_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.photo
    ADD CONSTRAINT photo_eg_id_fkey FOREIGN KEY (eg_id) REFERENCES public.entite_geol(id);


--
-- TOC entry 4371 (class 2606 OID 457488)
-- Name: photo photo_site_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.photo
    ADD CONSTRAINT photo_site_id_fkey FOREIGN KEY (site_id) REFERENCES public.site(id);


--
-- TOC entry 4368 (class 2606 OID 8332035)
-- Name: site site_ep_id_312a3358_fk_espace_protege_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.site
    ADD CONSTRAINT site_ep_id_312a3358_fk_espace_protege_id FOREIGN KEY (ep_id) REFERENCES public.espace_protege(id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- TOC entry 4375 (class 2606 OID 8332023)
-- Name: site_ontology site_ontology_qcm_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.site_ontology
    ADD CONSTRAINT site_ontology_qcm_id_fkey FOREIGN KEY (qcm_id) REFERENCES public.ontology(id);


--
-- TOC entry 4376 (class 2606 OID 8332029)
-- Name: site_ontology site_ontology_site_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.site_ontology
    ADD CONSTRAINT site_ontology_site_id_fkey FOREIGN KEY (site_id) REFERENCES public.site(id);


--
-- TOC entry 4369 (class 2606 OID 457311)
-- Name: site_qcm site_qcm_site_id_c3752863_fk_site_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.site_qcm
    ADD CONSTRAINT site_qcm_site_id_c3752863_fk_site_id FOREIGN KEY (site_id) REFERENCES public.site(id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


-- Completed on 2019-10-09 12:38:36

--
-- PostgreSQL database dump complete
--

