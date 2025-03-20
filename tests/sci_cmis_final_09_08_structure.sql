--
-- PostgreSQL database dump
--

-- Dumped from database version 16.4 (Ubuntu 16.4-1.pgdg22.04+2)
-- Dumped by pg_dump version 16.4 (Ubuntu 16.4-1.pgdg22.04+2)

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
-- Name: master; Type: SCHEMA; Schema: -; Owner: dev
--

CREATE SCHEMA master;


ALTER SCHEMA master OWNER TO dev;

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: postgres
--

-- *not* creating schema, since initdb creates it


ALTER SCHEMA public OWNER TO postgres;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS '';


--
-- Name: call_listing1_days_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.call_listing1_days_type AS ENUM (
    'NtN',
    'N'
);


ALTER TYPE public.call_listing1_days_type OWNER TO postgres;

--
-- Name: call_listing_days_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.call_listing_days_type AS ENUM (
    'NtN',
    'N'
);


ALTER TYPE public.call_listing_days_type OWNER TO postgres;

--
-- Name: country_display; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.country_display AS ENUM (
    'Y',
    'N'
);


ALTER TYPE public.country_display OWNER TO postgres;

--
-- Name: da_case_distribution_new_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.da_case_distribution_new_type AS ENUM (
    'M',
    'F'
);


ALTER TYPE public.da_case_distribution_new_type OWNER TO postgres;

--
-- Name: da_case_distribution_pilwrit_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.da_case_distribution_pilwrit_type AS ENUM (
    'M',
    'F'
);


ALTER TYPE public.da_case_distribution_pilwrit_type OWNER TO postgres;

--
-- Name: da_case_distribution_tri_new_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.da_case_distribution_tri_new_type AS ENUM (
    'M',
    'F'
);


ALTER TYPE public.da_case_distribution_tri_new_type OWNER TO postgres;

--
-- Name: da_case_distribution_tri_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.da_case_distribution_tri_type AS ENUM (
    'M',
    'F'
);


ALTER TYPE public.da_case_distribution_tri_type OWNER TO postgres;

--
-- Name: da_case_distribution_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.da_case_distribution_type AS ENUM (
    'M',
    'F'
);


ALTER TYPE public.da_case_distribution_type OWNER TO postgres;

--
-- Name: education_type_display; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.education_type_display AS ENUM (
    'Y',
    'N'
);


ALTER TYPE public.education_type_display OWNER TO postgres;

--
-- Name: heardt_board_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.heardt_board_type AS ENUM (
    'J',
    'C',
    'R',
    'S'
);


ALTER TYPE public.heardt_board_type OWNER TO postgres;

--
-- Name: heardt_webuse_board_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.heardt_webuse_board_type AS ENUM (
    'J',
    'C',
    'R',
    'S'
);


ALTER TYPE public.heardt_webuse_board_type OWNER TO postgres;

--
-- Name: last_heardt_board_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.last_heardt_board_type AS ENUM (
    'J',
    'C',
    'R',
    'S'
);


ALTER TYPE public.last_heardt_board_type OWNER TO postgres;

--
-- Name: last_heardt_webuse_board_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.last_heardt_webuse_board_type AS ENUM (
    'J',
    'C',
    'R',
    'S'
);


ALTER TYPE public.last_heardt_webuse_board_type OWNER TO postgres;

--
-- Name: master_bench_board_type_mb; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.master_bench_board_type_mb AS ENUM (
    'J',
    'S',
    'C',
    'R',
    'CC'
);


ALTER TYPE public.master_bench_board_type_mb OWNER TO postgres;

--
-- Name: msg_seen; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.msg_seen AS ENUM (
    'Y',
    'N'
);


ALTER TYPE public.msg_seen OWNER TO postgres;

--
-- Name: occupation_type_display; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.occupation_type_display AS ENUM (
    'Y',
    'N'
);


ALTER TYPE public.occupation_type_display OWNER TO postgres;

--
-- Name: party_cont_pro_info; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.party_cont_pro_info AS ENUM (
    'C',
    'P'
);


ALTER TYPE public.party_cont_pro_info OWNER TO postgres;

--
-- Name: registered_cases_display; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.registered_cases_display AS ENUM (
    'Y',
    'N'
);


ALTER TYPE public.registered_cases_display OWNER TO postgres;

--
-- Name: subheading_board_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.subheading_board_type AS ENUM (
    'J',
    'C',
    'R'
);


ALTER TYPE public.subheading_board_type OWNER TO postgres;

--
-- Name: tbl_user_status; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tbl_user_status AS ENUM (
    '0',
    '1'
);


ALTER TYPE public.tbl_user_status OWNER TO postgres;

--
-- Name: users_22092000_attend; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.users_22092000_attend AS ENUM (
    'P',
    'A'
);


ALTER TYPE public.users_22092000_attend OWNER TO postgres;

--
-- Name: users_attend; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.users_attend AS ENUM (
    'P',
    'A'
);


ALTER TYPE public.users_attend OWNER TO postgres;

--
-- Name: users_dump_attend; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.users_dump_attend AS ENUM (
    'P',
    'A'
);


ALTER TYPE public.users_dump_attend OWNER TO postgres;

--
-- Name: usersection_isda; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.usersection_isda AS ENUM (
    'Y',
    'N'
);


ALTER TYPE public.usersection_isda OWNER TO postgres;

--
-- Name: on_update_current_timestamp_abr_accused(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_abr_accused() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.acc_ent_time = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_abr_accused() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_act_main(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_act_main() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_act_main() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_act_section(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_act_section() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_act_section() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_caveat_diary_matching(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_caveat_diary_matching() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.ent_dt = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_caveat_diary_matching() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_data_tentative_dates(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_data_tentative_dates() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.entry_date = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_data_tentative_dates() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_data_tentative_dates_log(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_data_tentative_dates_log() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.entry_date = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_data_tentative_dates_log() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_dispose(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_dispose() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_dispose() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_dispose_delete(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_dispose_delete() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.entered_on = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_dispose_delete() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_docdetails(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_docdetails() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.lst_mdf = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_docdetails() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_docdetails_history(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_docdetails_history() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.lst_mdf = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_docdetails_history() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_fdr_records(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_fdr_records() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.updated_datetime = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_fdr_records() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_main(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_main() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_main() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_main_backup_data_correction(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_main_backup_data_correction() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_main_backup_data_correction() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_main_casetype_history(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_main_casetype_history() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_main_casetype_history() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_master_banks(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_master_banks() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.updated_datetime = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_master_banks() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_mobile_numbers_wa(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_mobile_numbers_wa() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.updated_on = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_mobile_numbers_wa() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_mul_category(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_mul_category() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.e_date = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_mul_category() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_njdg_act(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_njdg_act() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_njdg_act() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_njdg_category_transaction(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_njdg_category_transaction() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_njdg_category_transaction() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_njdg_lower_court(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_njdg_lower_court() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_njdg_lower_court() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_njdg_ordernet(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_njdg_ordernet() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_njdg_ordernet() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_njdg_ordernet_16102022(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_njdg_ordernet_16102022() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_njdg_ordernet_16102022() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_njdg_stats(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_njdg_stats() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.created_date = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_njdg_stats() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_njdg_transaction(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_njdg_transaction() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_njdg_transaction() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_njdg_transaction_bck_11102022(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_njdg_transaction_bck_11102022() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_njdg_transaction_bck_11102022() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_ordernet(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_ordernet() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.create_modify = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_ordernet() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_t_category_master(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_t_category_master() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.access_dated = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_t_category_master() OWNER TO postgres;

--
-- Name: on_update_current_timestamp_t_doc_details(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.on_update_current_timestamp_t_doc_details() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
   NEW.access_dated = now();
   RETURN NEW;
END;
$$;


ALTER FUNCTION public.on_update_current_timestamp_t_doc_details() OWNER TO postgres;

--
-- Name: tentative_section(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.tentative_section(diary_no character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
DECLARE
    result_section VARCHAR;
BEGIN
    -- Replace this logic with the actual logic needed to determine the tentative section
    -- Example: let's assume it simply returns the first character of the diary number
    result_section := LEFT(diary_no, 1); 
    
    -- You can implement more complex logic here based on your actual requirements
    -- Example: You might want to query another table based on diary_no
    
    RETURN result_section;
END;
$$;


ALTER FUNCTION public.tentative_section(diary_no character varying) OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: act_master; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.act_master (
    id bigint NOT NULL,
    act_name character varying(100) NOT NULL,
    act_name_h character varying(200) NOT NULL,
    year bigint NOT NULL,
    actno bigint NOT NULL,
    state_id bigint NOT NULL,
    display character varying(1) NOT NULL,
    old_id bigint NOT NULL,
    old_act_code bigint NOT NULL
);


ALTER TABLE master.act_master OWNER TO postgres;

--
-- Name: act_master_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.act_master_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.act_master_id_seq OWNER TO postgres;

--
-- Name: act_master_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.act_master_id_seq OWNED BY master.act_master.id;


--
-- Name: act_section; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.act_section (
    act_id bigint,
    section character varying(200) NOT NULL,
    entdt timestamp with time zone,
    "user" bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying,
    create_modify timestamp with time zone
);


ALTER TABLE master.act_section OWNER TO postgres;

--
-- Name: admin_icmis_usertype_map; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.admin_icmis_usertype_map (
    id bigint NOT NULL,
    admin_designation_id bigint NOT NULL,
    admin_designation_name character varying(100),
    icmis_usertype_id bigint NOT NULL,
    icmis_usertype_name character varying(100)
);


ALTER TABLE master.admin_icmis_usertype_map OWNER TO postgres;

--
-- Name: admin_icmis_usertype_map_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.admin_icmis_usertype_map_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.admin_icmis_usertype_map_id_seq OWNER TO postgres;

--
-- Name: admin_icmis_usertype_map_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.admin_icmis_usertype_map_id_seq OWNED BY master.admin_icmis_usertype_map.id;


--
-- Name: agency_master; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.agency_master (
    section_code bigint,
    case_type character varying(10000),
    id character varying(10000),
    subject_category character varying(45)
);


ALTER TABLE master.agency_master OWNER TO postgres;

--
-- Name: amicus_curiae_allotment_direction; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.amicus_curiae_allotment_direction (
    id bigint NOT NULL,
    direction_given_by character varying(45),
    is_deleted character varying(2) DEFAULT 'f'::character varying,
    updated_by bigint,
    updated_on timestamp with time zone
);


ALTER TABLE master.amicus_curiae_allotment_direction OWNER TO postgres;

--
-- Name: amicus_curiae_allotment_direction_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.amicus_curiae_allotment_direction_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.amicus_curiae_allotment_direction_id_seq OWNER TO postgres;

--
-- Name: amicus_curiae_allotment_direction_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.amicus_curiae_allotment_direction_id_seq OWNED BY master.amicus_curiae_allotment_direction.id;


--
-- Name: authority; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.authority (
    authcode bigint DEFAULT 0 NOT NULL,
    authdesc character varying(100),
    usercode bigint,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    authtype character(1) DEFAULT 'O'::bpchar NOT NULL
);


ALTER TABLE master.authority OWNER TO postgres;

--
-- Name: bar; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.bar (
    bar_id bigint NOT NULL,
    title character varying(10) NOT NULL,
    name character varying(200) NOT NULL,
    rel character varying(1) NOT NULL,
    fname character varying(100) NOT NULL,
    mname character varying(100) NOT NULL,
    dob date,
    paddress character varying(200) NOT NULL,
    pcity character varying(30) NOT NULL,
    caddress character varying(200) NOT NULL,
    ccity character varying(20) NOT NULL,
    pp character varying(20) NOT NULL,
    sex character varying(5) NOT NULL,
    "cast" character varying(5) NOT NULL,
    phno character varying(20) NOT NULL,
    mobile bigint NOT NULL,
    email character varying(100) NOT NULL,
    enroll_no character varying(50) NOT NULL,
    enroll_date date,
    isdead character(1) DEFAULT 'N'::bpchar NOT NULL,
    date_of_dead date,
    passing_year bigint NOT NULL,
    if_aor character(1) NOT NULL,
    state_id bigint NOT NULL,
    bentuser bigint NOT NULL,
    bentdt timestamp with time zone,
    bupuser bigint NOT NULL,
    bupdt date,
    aor_code bigint NOT NULL,
    if_sen character(1) NOT NULL,
    sc_from_dt date,
    sc_to_date date,
    cmis_state_id bigint NOT NULL,
    agency_code bigint NOT NULL,
    if_other character varying(1),
    name_hindi character varying(200),
    title_hindi character varying(50),
    create_modify timestamp with time zone,
    updated_on timestamp with time zone,
    updated_by bigint,
    updated_by_ip character varying(100)
);


ALTER TABLE master.bar OWNER TO postgres;

--
-- Name: bar_bar_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.bar_bar_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.bar_bar_id_seq OWNER TO postgres;

--
-- Name: bar_bar_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.bar_bar_id_seq OWNED BY master.bar.bar_id;


--
-- Name: bench; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.bench (
    b_code character(2),
    b_name character varying(20),
    j_bench character varying(5) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE master.bench OWNER TO postgres;

--
-- Name: call_listing_days; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.call_listing_days (
    id bigint NOT NULL,
    weekday bigint,
    listonday bigint,
    type public.call_listing_days_type,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE master.call_listing_days OWNER TO postgres;

--
-- Name: call_listing_days_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.call_listing_days_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.call_listing_days_id_seq OWNER TO postgres;

--
-- Name: call_listing_days_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.call_listing_days_id_seq OWNED BY master.call_listing_days.id;


--
-- Name: case_remarks_head; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.case_remarks_head (
    sno bigint NOT NULL,
    head character varying(100) NOT NULL,
    pending_text character varying(200) NOT NULL,
    side character varying(1) NOT NULL,
    cis_disp_code bigint NOT NULL,
    cat_head_id bigint,
    rgo_color character(1) NOT NULL,
    compliance_limit_in_day bigint NOT NULL,
    fixed_date character(1),
    stage character(1) NOT NULL,
    priority bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    national_short_name character varying(100),
    national_code bigint,
    national_remark_type character varying(200),
    head_hindi character varying(200)
);


ALTER TABLE master.case_remarks_head OWNER TO postgres;

--
-- Name: case_remarks_head_sno_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.case_remarks_head_sno_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.case_remarks_head_sno_seq OWNER TO postgres;

--
-- Name: case_remarks_head_sno_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.case_remarks_head_sno_seq OWNED BY master.case_remarks_head.sno;


--
-- Name: case_status_flag; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.case_status_flag (
    id bigint NOT NULL,
    flag_name character varying(45) NOT NULL,
    display_flag character varying(45) NOT NULL,
    updated_on timestamp with time zone,
    always_allowed_users character varying(500),
    from_date timestamp with time zone,
    to_date timestamp with time zone,
    ip character varying(100)
);


ALTER TABLE master.case_status_flag OWNER TO postgres;

--
-- Name: case_status_flag_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.case_status_flag_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.case_status_flag_id_seq OWNER TO postgres;

--
-- Name: case_status_flag_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.case_status_flag_id_seq OWNED BY master.case_status_flag.id;


--
-- Name: case_verify_by_sec_remark; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.case_verify_by_sec_remark (
    id bigint NOT NULL,
    remarks character varying(100)
);


ALTER TABLE master.case_verify_by_sec_remark OWNER TO postgres;

--
-- Name: case_verify_by_sec_remark_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.case_verify_by_sec_remark_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.case_verify_by_sec_remark_id_seq OWNER TO postgres;

--
-- Name: case_verify_by_sec_remark_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.case_verify_by_sec_remark_id_seq OWNED BY master.case_verify_by_sec_remark.id;


--
-- Name: caselaw; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.caselaw (
    casetype character varying(5) DEFAULT ''::character varying NOT NULL,
    lawcode bigint DEFAULT 0 NOT NULL,
    nature character(1) DEFAULT ''::bpchar NOT NULL,
    law character varying(500),
    display character(1) DEFAULT ''::bpchar NOT NULL,
    case_code bigint NOT NULL,
    id bigint NOT NULL
);


ALTER TABLE master.caselaw OWNER TO postgres;

--
-- Name: caselaw_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.caselaw_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.caselaw_id_seq OWNER TO postgres;

--
-- Name: caselaw_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.caselaw_id_seq OWNED BY master.caselaw.id;


--
-- Name: casetype; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.casetype (
    casecode bigint DEFAULT 0 NOT NULL,
    casename character varying(100),
    skey character varying(5),
    display character(1) DEFAULT ''::bpchar NOT NULL,
    nature character(1) DEFAULT 'C'::bpchar NOT NULL,
    cs_m_f character(1),
    order_no bigint,
    company bigint DEFAULT '999'::bigint NOT NULL,
    short_description character varying(30) NOT NULL,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    limitation bigint NOT NULL,
    case_type_code character varying(2) NOT NULL,
    is_deleted character varying(6) NOT NULL,
    sc_case_type_code bigint NOT NULL,
    case_type_judis character varying(50) NOT NULL,
    diary_code bigint NOT NULL,
    national_code bigint NOT NULL,
    national_case_type character varying(100),
    national_relief_type character varying(45),
    jurisdiction character varying(45),
    national_relief_code bigint,
    casename_hindi character varying(200),
    short_description_hindi character varying(200)
);


ALTER TABLE master.casetype OWNER TO postgres;

--
-- Name: cat_jud_ratio; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.cat_jud_ratio (
    cat_id bigint NOT NULL,
    cat_name character varying(100) NOT NULL,
    judge bigint NOT NULL,
    next_dt date NOT NULL,
    bail_top bigint NOT NULL,
    orders bigint NOT NULL,
    fresh bigint NOT NULL,
    fresh_no_notice bigint NOT NULL,
    an_fd bigint NOT NULL,
    cnt bigint NOT NULL,
    ratio_cnt double precision NOT NULL,
    ent_dt timestamp with time zone,
    usercode bigint NOT NULL
);


ALTER TABLE master.cat_jud_ratio OWNER TO postgres;

--
-- Name: cnt_caveat; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.cnt_caveat (
    id bigint NOT NULL,
    caveat_year character varying(4) NOT NULL,
    max_caveat_no bigint NOT NULL
);


ALTER TABLE master.cnt_caveat OWNER TO postgres;

--
-- Name: cnt_caveat_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.cnt_caveat_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.cnt_caveat_id_seq OWNER TO postgres;

--
-- Name: cnt_caveat_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.cnt_caveat_id_seq OWNED BY master.cnt_caveat.id;


--
-- Name: cnt_diary_no; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.cnt_diary_no (
    id bigint NOT NULL,
    diary_no_year character varying(4) NOT NULL,
    max_diary_no bigint NOT NULL
);


ALTER TABLE master.cnt_diary_no OWNER TO postgres;

--
-- Name: cnt_diary_no_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.cnt_diary_no_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.cnt_diary_no_id_seq OWNER TO postgres;

--
-- Name: cnt_diary_no_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.cnt_diary_no_id_seq OWNED BY master.cnt_diary_no.id;


--
-- Name: cnt_token; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.cnt_token (
    id bigint NOT NULL,
    date date,
    token_no bigint NOT NULL
);


ALTER TABLE master.cnt_token OWNER TO postgres;

--
-- Name: cnt_token_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.cnt_token_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.cnt_token_id_seq OWNER TO postgres;

--
-- Name: cnt_token_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.cnt_token_id_seq OWNED BY master.cnt_token.id;


--
-- Name: content_for_latestupdates; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.content_for_latestupdates (
    id bigint NOT NULL,
    content_id bigint NOT NULL,
    f_date date,
    t_date date,
    memo_number character varying(50),
    title_en text NOT NULL,
    file_name character varying(500),
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    ent_dt timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    "user" bigint,
    ip character varying(32) NOT NULL,
    mac_address character varying(20),
    deleted_on timestamp with time zone,
    deleted_by bigint,
    deleted_from_ip character varying(32)
);


ALTER TABLE master.content_for_latestupdates OWNER TO postgres;

--
-- Name: content_for_latestupdates_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.content_for_latestupdates_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.content_for_latestupdates_id_seq OWNER TO postgres;

--
-- Name: content_for_latestupdates_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.content_for_latestupdates_id_seq OWNED BY master.content_for_latestupdates.id;


--
-- Name: copy_category; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.copy_category (
    id bigint NOT NULL,
    code character(2) NOT NULL,
    description character varying(45) NOT NULL,
    charges bigint NOT NULL,
    urgent_fee bigint DEFAULT '0'::bigint NOT NULL,
    per_certification_fee bigint DEFAULT '0'::bigint,
    from_date date,
    to_date date,
    per_page bigint DEFAULT '1'::bigint NOT NULL
);


ALTER TABLE master.copy_category OWNER TO postgres;

--
-- Name: copy_category_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.copy_category_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.copy_category_id_seq OWNER TO postgres;

--
-- Name: copy_category_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.copy_category_id_seq OWNED BY master.copy_category.id;


--
-- Name: copying_reasons_for_rejection; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.copying_reasons_for_rejection (
    id bigint NOT NULL,
    reasons character varying(100) NOT NULL,
    user_id bigint NOT NULL,
    entry_time timestamp with time zone,
    is_active character varying(1) NOT NULL,
    ip_address character varying(45) NOT NULL
);


ALTER TABLE master.copying_reasons_for_rejection OWNER TO postgres;

--
-- Name: copying_reasons_for_rejection_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.copying_reasons_for_rejection_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.copying_reasons_for_rejection_id_seq OWNER TO postgres;

--
-- Name: copying_reasons_for_rejection_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.copying_reasons_for_rejection_id_seq OWNED BY master.copying_reasons_for_rejection.id;


--
-- Name: copying_role; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.copying_role (
    id bigint NOT NULL,
    applicant_type_id character varying(45) NOT NULL,
    application_type_id character varying(45),
    role_assign_by bigint NOT NULL,
    role_assign_to bigint,
    from_date timestamp with time zone,
    to_date timestamp with time zone,
    status character varying(1) NOT NULL,
    ip_address character varying(45)
);


ALTER TABLE master.copying_role OWNER TO postgres;

--
-- Name: copying_role_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.copying_role_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.copying_role_id_seq OWNER TO postgres;

--
-- Name: copying_role_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.copying_role_id_seq OWNED BY master.copying_role.id;


--
-- Name: country; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.country (
    id bigint NOT NULL,
    country_name character varying(50) NOT NULL,
    country_code bigint NOT NULL,
    short_description character varying(10) NOT NULL,
    display public.country_display DEFAULT 'Y'::public.country_display NOT NULL
);


ALTER TABLE master.country OWNER TO postgres;

--
-- Name: country_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.country_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.country_id_seq OWNER TO postgres;

--
-- Name: country_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.country_id_seq OWNED BY master.country.id;


--
-- Name: court_ip; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.court_ip (
    sno bigint NOT NULL,
    court_no smallint NOT NULL,
    ip_address character varying(40) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    entered_by bigint NOT NULL,
    entered_on timestamp with time zone,
    entered_ip character varying(40) NOT NULL,
    deleted_by bigint,
    deleted_ip character varying(45),
    deleted_on timestamp with time zone
);


ALTER TABLE master.court_ip OWNER TO postgres;

--
-- Name: court_ip_sno_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.court_ip_sno_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.court_ip_sno_seq OWNER TO postgres;

--
-- Name: court_ip_sno_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.court_ip_sno_seq OWNED BY master.court_ip.sno;


--
-- Name: court_masters; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.court_masters (
    id bigint NOT NULL,
    usercode bigint,
    is_nsh character(1) DEFAULT 'Y'::bpchar,
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE master.court_masters OWNER TO postgres;

--
-- Name: court_masters_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.court_masters_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.court_masters_id_seq OWNER TO postgres;

--
-- Name: court_masters_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.court_masters_id_seq OWNED BY master.court_masters.id;


--
-- Name: da_case_distribution; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.da_case_distribution (
    id bigint NOT NULL,
    case_type smallint NOT NULL,
    case_from bigint NOT NULL,
    case_f_yr bigint NOT NULL,
    case_to bigint NOT NULL,
    case_t_yr bigint NOT NULL,
    state bigint NOT NULL,
    subcat0 bigint NOT NULL,
    subcat1 bigint NOT NULL,
    subcat2 bigint NOT NULL,
    dacode bigint NOT NULL,
    entdt timestamp with time zone,
    entuser bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar,
    type public.da_case_distribution_type DEFAULT 'M'::public.da_case_distribution_type,
    upuser bigint NOT NULL,
    updt timestamp with time zone
);


ALTER TABLE master.da_case_distribution OWNER TO postgres;

--
-- Name: da_case_distribution_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.da_case_distribution_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.da_case_distribution_id_seq OWNER TO postgres;

--
-- Name: da_case_distribution_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.da_case_distribution_id_seq OWNED BY master.da_case_distribution.id;


--
-- Name: da_case_distribution_new; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.da_case_distribution_new (
    id bigint NOT NULL,
    case_type smallint NOT NULL,
    case_from bigint NOT NULL,
    case_f_yr date,
    case_to bigint NOT NULL,
    case_t_yr date,
    state bigint NOT NULL,
    subcat0 bigint NOT NULL,
    subcat1 bigint NOT NULL,
    subcat2 bigint NOT NULL,
    dacode bigint NOT NULL,
    entdt timestamp with time zone,
    entuser bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar,
    type public.da_case_distribution_new_type DEFAULT 'M'::public.da_case_distribution_new_type,
    upuser bigint NOT NULL,
    updt timestamp with time zone
);


ALTER TABLE master.da_case_distribution_new OWNER TO postgres;

--
-- Name: da_case_distribution_new_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.da_case_distribution_new_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.da_case_distribution_new_id_seq OWNER TO postgres;

--
-- Name: da_case_distribution_new_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.da_case_distribution_new_id_seq OWNED BY master.da_case_distribution_new.id;


--
-- Name: da_case_distribution_pilwrit; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.da_case_distribution_pilwrit (
    id bigint NOT NULL,
    case_type smallint NOT NULL,
    case_from bigint NOT NULL,
    case_f_yr date,
    case_to bigint NOT NULL,
    case_t_yr date,
    state bigint NOT NULL,
    subcat0 bigint NOT NULL,
    subcat1 bigint NOT NULL,
    subcat2 bigint NOT NULL,
    dacode bigint NOT NULL,
    entdt timestamp with time zone,
    entuser bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar,
    type public.da_case_distribution_pilwrit_type DEFAULT 'M'::public.da_case_distribution_pilwrit_type,
    upuser bigint NOT NULL,
    updt timestamp with time zone
);


ALTER TABLE master.da_case_distribution_pilwrit OWNER TO postgres;

--
-- Name: da_case_distribution_pilwrit_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.da_case_distribution_pilwrit_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.da_case_distribution_pilwrit_id_seq OWNER TO postgres;

--
-- Name: da_case_distribution_pilwrit_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.da_case_distribution_pilwrit_id_seq OWNED BY master.da_case_distribution_pilwrit.id;


--
-- Name: da_case_distribution_tri; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.da_case_distribution_tri (
    id bigint NOT NULL,
    case_type smallint NOT NULL,
    case_from bigint NOT NULL,
    case_f_yr bigint NOT NULL,
    case_to bigint NOT NULL,
    case_t_yr bigint NOT NULL,
    state bigint NOT NULL,
    subcat0 bigint NOT NULL,
    subcat1 bigint NOT NULL,
    subcat2 bigint NOT NULL,
    dacode bigint NOT NULL,
    entdt timestamp with time zone,
    entuser bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar,
    type public.da_case_distribution_tri_type DEFAULT 'M'::public.da_case_distribution_tri_type,
    upuser bigint NOT NULL,
    updt timestamp with time zone,
    ref_agency character varying(1200)
);


ALTER TABLE master.da_case_distribution_tri OWNER TO postgres;

--
-- Name: da_case_distribution_tri_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.da_case_distribution_tri_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.da_case_distribution_tri_id_seq OWNER TO postgres;

--
-- Name: da_case_distribution_tri_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.da_case_distribution_tri_id_seq OWNED BY master.da_case_distribution_tri.id;


--
-- Name: da_case_distribution_tri_new; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.da_case_distribution_tri_new (
    id bigint NOT NULL,
    case_type smallint NOT NULL,
    case_from bigint NOT NULL,
    case_f_yr date,
    case_to bigint NOT NULL,
    case_t_yr date,
    state bigint NOT NULL,
    subcat0 bigint NOT NULL,
    subcat1 bigint NOT NULL,
    subcat2 bigint NOT NULL,
    dacode bigint NOT NULL,
    entdt timestamp with time zone,
    entuser bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar,
    type public.da_case_distribution_tri_new_type DEFAULT 'M'::public.da_case_distribution_tri_new_type,
    upuser bigint NOT NULL,
    updt timestamp with time zone,
    ref_agency bigint NOT NULL
);


ALTER TABLE master.da_case_distribution_tri_new OWNER TO postgres;

--
-- Name: da_case_distribution_tri_new_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.da_case_distribution_tri_new_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.da_case_distribution_tri_new_id_seq OWNER TO postgres;

--
-- Name: da_case_distribution_tri_new_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.da_case_distribution_tri_new_id_seq OWNED BY master.da_case_distribution_tri_new.id;


--
-- Name: defect_policy; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.defect_policy (
    id bigint NOT NULL,
    no_of_days bigint NOT NULL,
    master_module bigint NOT NULL,
    from_date date,
    to_date date
);


ALTER TABLE master.defect_policy OWNER TO postgres;

--
-- Name: defect_policy_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.defect_policy_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.defect_policy_id_seq OWNER TO postgres;

--
-- Name: defect_policy_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.defect_policy_id_seq OWNED BY master.defect_policy.id;


--
-- Name: defect_record_paperbook; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.defect_record_paperbook (
    id bigint NOT NULL,
    diary_no bigint,
    section_id bigint,
    court_fees bigint,
    defect_notify_date date,
    rack_no bigint,
    shelf_no bigint,
    display character varying(5),
    ent_dt timestamp with time zone,
    upd_dt timestamp with time zone,
    ent_userid bigint,
    upd_userid bigint
);


ALTER TABLE master.defect_record_paperbook OWNER TO postgres;

--
-- Name: defect_record_paperbook_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.defect_record_paperbook_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.defect_record_paperbook_id_seq OWNER TO postgres;

--
-- Name: defect_record_paperbook_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.defect_record_paperbook_id_seq OWNED BY master.defect_record_paperbook.id;


--
-- Name: delhi_district_court; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.delhi_district_court (
    id bigint NOT NULL,
    state_code bigint NOT NULL,
    district_code bigint NOT NULL,
    court_name character varying(100) NOT NULL
);


ALTER TABLE master.delhi_district_court OWNER TO postgres;

--
-- Name: deptt; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.deptt (
    deptcode bigint,
    deptype character(1),
    deptname character varying(100),
    dm bigint NOT NULL,
    d1 bigint NOT NULL,
    d2 bigint NOT NULL,
    d3 bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar,
    dept_code_sc bigint NOT NULL,
    deptemail character varying(50) NOT NULL,
    deptmobile bigint NOT NULL,
    drupal_id bigint NOT NULL
);


ALTER TABLE master.deptt OWNER TO postgres;

--
-- Name: dev; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.dev (
    aor_code bigint,
    cname character varying(100),
    cfname character varying(100),
    regdate character varying(100),
    eino character varying(5)
);


ALTER TABLE master.dev OWNER TO postgres;

--
-- Name: dev1; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.dev1 (
    aor_code bigint,
    cname character varying(100),
    cfname character varying(100),
    regdate character varying(100),
    eino character varying(5)
);


ALTER TABLE master.dev1 OWNER TO postgres;

--
-- Name: disposal; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.disposal (
    dispcode bigint NOT NULL,
    dispname character varying(100),
    display character(1) DEFAULT ''::bpchar NOT NULL,
    spk character(1) NOT NULL,
    sc_code bigint NOT NULL,
    short_name character varying(45),
    national_code bigint,
    ndisposal_type_short character varying(45),
    dispname_hindi character varying(100),
    short_name_hindi character varying(200)
);


ALTER TABLE master.disposal OWNER TO postgres;

--
-- Name: district; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.district (
    dcode bigint DEFAULT 0 NOT NULL,
    dname character varying(100),
    chby character(2) DEFAULT '01'::bpchar,
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE master.district OWNER TO postgres;

--
-- Name: dockount; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.dockount (
    year bigint DEFAULT 0 NOT NULL,
    knt bigint DEFAULT 0 NOT NULL
);


ALTER TABLE master.dockount OWNER TO postgres;

--
-- Name: docmaster; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.docmaster (
    doccode bigint DEFAULT 0 NOT NULL,
    doccode1 bigint DEFAULT 0 NOT NULL,
    docdesc character varying(100) DEFAULT ''::character varying NOT NULL,
    docfee bigint DEFAULT 0 NOT NULL,
    kntgrp character(3),
    doctype boolean DEFAULT true NOT NULL,
    display character(1),
    old_id bigint NOT NULL,
    relief_code bigint NOT NULL,
    remark1 character varying(50) NOT NULL,
    remark2 character varying(50) NOT NULL,
    listable character varying(50) NOT NULL,
    sc_doc_code character varying(50),
    not_reg_if_pen bigint NOT NULL,
    doc_ia_type character varying(20) NOT NULL,
    docdesc_hindi character varying(200)
);


ALTER TABLE master.docmaster OWNER TO postgres;

--
-- Name: drop_reason; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.drop_reason (
    id bigint NOT NULL,
    reason character varying(50) NOT NULL,
    reason_type bigint NOT NULL,
    display character(1) NOT NULL
);


ALTER TABLE master.drop_reason OWNER TO postgres;

--
-- Name: drop_reason_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.drop_reason_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.drop_reason_id_seq OWNER TO postgres;

--
-- Name: drop_reason_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.drop_reason_id_seq OWNED BY master.drop_reason.id;


--
-- Name: ec_pil_reference_mapping; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ec_pil_reference_mapping (
    id bigint NOT NULL,
    ec_pil_id bigint,
    ec_pil_reference_id bigint,
    adm_updated_by bigint,
    updated_on timestamp with time zone,
    is_deleted character varying(5) DEFAULT 'f'::character varying
);


ALTER TABLE master.ec_pil_reference_mapping OWNER TO postgres;

--
-- Name: ec_pil_reference_mapping_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ec_pil_reference_mapping_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ec_pil_reference_mapping_id_seq OWNER TO postgres;

--
-- Name: ec_pil_reference_mapping_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ec_pil_reference_mapping_id_seq OWNED BY master.ec_pil_reference_mapping.id;


--
-- Name: education_type; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.education_type (
    id bigint NOT NULL,
    edu_desc character varying(50) NOT NULL,
    display public.education_type_display DEFAULT 'Y'::public.education_type_display NOT NULL,
    entdt timestamp with time zone,
    entuser bigint NOT NULL,
    updt timestamp with time zone,
    upuser bigint NOT NULL
);


ALTER TABLE master.education_type OWNER TO postgres;

--
-- Name: education_type_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.education_type_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.education_type_id_seq OWNER TO postgres;

--
-- Name: education_type_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.education_type_id_seq OWNED BY master.education_type.id;


--
-- Name: emp_desg; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.emp_desg (
    desgcode bigint NOT NULL,
    scale character varying(50) NOT NULL,
    oldscale character varying(50) NOT NULL,
    desgname character varying(50) NOT NULL,
    desgname1 character varying(50) NOT NULL,
    payband character varying(50) NOT NULL,
    minpay bigint,
    maxpay bigint,
    gpay bigint,
    ta bigint,
    "group" character(1) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE master.emp_desg OWNER TO postgres;

--
-- Name: emp_details_t; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.emp_details_t (
    empid character varying(9) NOT NULL,
    name character varying(20) NOT NULL,
    fath_hus_name character varying(50) NOT NULL,
    relation character(1) NOT NULL,
    address character varying(60) NOT NULL,
    paddress character varying(60) NOT NULL,
    gender character(1) NOT NULL,
    dob date,
    post bigint NOT NULL,
    mobile bigint NOT NULL,
    display character varying(1) NOT NULL,
    service character varying(1) NOT NULL
);


ALTER TABLE master.emp_details_t OWNER TO postgres;

--
-- Name: escr_users; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.escr_users (
    id bigint NOT NULL,
    usercode bigint NOT NULL,
    role bigint NOT NULL
);


ALTER TABLE master.escr_users OWNER TO postgres;

--
-- Name: escr_users_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.escr_users_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.escr_users_id_seq OWNER TO postgres;

--
-- Name: escr_users_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.escr_users_id_seq OWNED BY master.escr_users.id;


--
-- Name: event_master; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.event_master (
    event_code bigint NOT NULL,
    event_name character varying(100) NOT NULL
);


ALTER TABLE master.event_master OWNER TO postgres;

--
-- Name: event_master_event_code_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.event_master_event_code_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.event_master_event_code_seq OWNER TO postgres;

--
-- Name: event_master_event_code_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.event_master_event_code_seq OWNED BY master.event_master.event_code;


--
-- Name: godown_user_allocation; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.godown_user_allocation (
    id bigint NOT NULL,
    usercode bigint,
    casetype_id bigint,
    caseyear bigint,
    case_from bigint,
    case_to bigint,
    case_grp text
);


ALTER TABLE master.godown_user_allocation OWNER TO postgres;

--
-- Name: holidays; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.holidays (
    hdate date NOT NULL,
    hname character varying(100) NOT NULL,
    emp_hol bigint NOT NULL
);


ALTER TABLE master.holidays OWNER TO postgres;

--
-- Name: icmis_faqs; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.icmis_faqs (
    id bigint NOT NULL,
    question text NOT NULL,
    answer text NOT NULL,
    main_menu character varying(50) NOT NULL,
    sub_menu character varying(50),
    created_on timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_on timestamp with time zone
);


ALTER TABLE master.icmis_faqs OWNER TO postgres;

--
-- Name: icmis_faqs_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.icmis_faqs_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.icmis_faqs_id_seq OWNER TO postgres;

--
-- Name: icmis_faqs_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.icmis_faqs_id_seq OWNED BY master.icmis_faqs.id;


--
-- Name: icmis_menu; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.icmis_menu (
    "-- --------------------------------------------------------" character varying(128)
);


ALTER TABLE master.icmis_menu OWNER TO postgres;

--
-- Name: id_proof_master; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.id_proof_master (
    id bigint NOT NULL,
    id_name character varying(60) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    entry_on timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE master.id_proof_master OWNER TO postgres;

--
-- Name: id_proof_master_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.id_proof_master_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.id_proof_master_id_seq OWNER TO postgres;

--
-- Name: id_proof_master_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.id_proof_master_id_seq OWNED BY master.id_proof_master.id;


--
-- Name: initialization; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.initialization (
    branch_name character varying(10) NOT NULL,
    code character varying(2) NOT NULL
);


ALTER TABLE master.initialization OWNER TO postgres;

--
-- Name: intercasetype; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.intercasetype (
    main_casecode bigint DEFAULT 0 NOT NULL,
    main_casename character varying(100) DEFAULT ''::character varying NOT NULL,
    lc_casecode bigint DEFAULT 0 NOT NULL,
    lc_casename character varying(100) DEFAULT ''::character varying NOT NULL,
    key bigint DEFAULT 0 NOT NULL
);


ALTER TABLE master.intercasetype OWNER TO postgres;

--
-- Name: intercasetype_new; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.intercasetype_new (
    main_casecode bigint NOT NULL,
    main_casename character varying(100) NOT NULL,
    lc_casecode bigint NOT NULL,
    lc_casename character varying(100) NOT NULL,
    key bigint NOT NULL,
    avail character(1) NOT NULL,
    old_code bigint NOT NULL
);


ALTER TABLE master.intercasetype_new OWNER TO postgres;

--
-- Name: jail_master; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.jail_master (
    loc_id character varying(50) NOT NULL,
    loc_no bigint NOT NULL,
    loc_det character varying(150) NOT NULL,
    loc_address character varying(150) NOT NULL,
    state_code bigint NOT NULL,
    district_code bigint NOT NULL,
    loc_type character varying(5) NOT NULL,
    loc_sub_type character varying(5) NOT NULL,
    jail_name character varying(150) NOT NULL,
    police_state_code bigint NOT NULL,
    police_state character varying(150) NOT NULL,
    police_district_code bigint NOT NULL,
    police_district character varying(150) NOT NULL,
    police_station_code bigint NOT NULL,
    police_station_name character varying(150) NOT NULL,
    cmis_state bigint NOT NULL,
    cmis_district_id bigint NOT NULL,
    lgd_state_code bigint NOT NULL,
    lgd_district_code bigint NOT NULL,
    prison_district_name character varying(150) NOT NULL,
    lgd_subdistrict_code bigint NOT NULL
);


ALTER TABLE master.jail_master OWNER TO postgres;

--
-- Name: judge; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.judge (
    jcode bigint DEFAULT 0 NOT NULL,
    jname character varying(50) NOT NULL,
    first_name character varying(40) NOT NULL,
    title character varying(30) NOT NULL,
    sur_name character varying(40) NOT NULL,
    jcourt bigint DEFAULT 0 NOT NULL,
    abbreviation character varying(15) NOT NULL,
    is_retired character(1) DEFAULT 'N'::bpchar NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    appointment_date date,
    to_dt date,
    cji_date date,
    jtype character varying(2) NOT NULL,
    entuser bigint DEFAULT 0 NOT NULL,
    entdt timestamp with time zone,
    judge_seniority bigint,
    national_uid character varying(45),
    judge_desg_code bigint,
    judgename_hindi character varying(500)
);


ALTER TABLE master.judge OWNER TO postgres;

--
-- Name: judge_category; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.judge_category (
    id bigint NOT NULL,
    j1 bigint,
    submaster_id bigint,
    priority bigint,
    from_dt date,
    ent_dt timestamp with time zone,
    usercode bigint,
    display character(1),
    to_dt date,
    to_dt_ent_dt timestamp with time zone,
    to_dt_usercode bigint,
    m_f character varying(45)
);


ALTER TABLE master.judge_category OWNER TO postgres;

--
-- Name: judge_category_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.judge_category_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.judge_category_id_seq OWNER TO postgres;

--
-- Name: judge_category_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.judge_category_id_seq OWNED BY master.judge_category.id;


--
-- Name: judge_desg; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.judge_desg (
    desgname character varying(50),
    national_code character varying(45),
    desgcode bigint NOT NULL
);


ALTER TABLE master.judge_desg OWNER TO postgres;

--
-- Name: judge_desg_desgcode_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.judge_desg_desgcode_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.judge_desg_desgcode_seq OWNER TO postgres;

--
-- Name: judge_desg_desgcode_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.judge_desg_desgcode_seq OWNED BY master.judge_desg.desgcode;


--
-- Name: kounter; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.kounter (
    id bigint NOT NULL,
    year bigint DEFAULT 0 NOT NULL,
    knt bigint DEFAULT 0 NOT NULL,
    casetype_id bigint
);


ALTER TABLE master.kounter OWNER TO postgres;

--
-- Name: kounter_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.kounter_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.kounter_id_seq OWNER TO postgres;

--
-- Name: kounter_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.kounter_id_seq OWNED BY master.kounter.id;


--
-- Name: law_firm; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.law_firm (
    law_id bigint NOT NULL,
    law_firm_name character varying(50) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE master.law_firm OWNER TO postgres;

--
-- Name: law_firm_adv; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.law_firm_adv (
    id bigint NOT NULL,
    law_firm_id bigint NOT NULL,
    enroll_no character varying(10) NOT NULL,
    enroll_yr bigint NOT NULL,
    state_id bigint NOT NULL,
    from_date date,
    to_date date,
    display character(1) NOT NULL,
    entry_date timestamp with time zone
);


ALTER TABLE master.law_firm_adv OWNER TO postgres;

--
-- Name: law_firm_adv_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.law_firm_adv_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.law_firm_adv_id_seq OWNER TO postgres;

--
-- Name: law_firm_adv_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.law_firm_adv_id_seq OWNED BY master.law_firm_adv.id;


--
-- Name: law_firm_law_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.law_firm_law_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.law_firm_law_id_seq OWNER TO postgres;

--
-- Name: law_firm_law_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.law_firm_law_id_seq OWNED BY master.law_firm.law_id;


--
-- Name: lc_casetype; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.lc_casetype (
    lccasecode bigint DEFAULT 0 NOT NULL,
    lccasename character varying(100),
    corttyp character(1) DEFAULT 'L'::bpchar NOT NULL,
    display character(1) DEFAULT ''::bpchar NOT NULL,
    skey character varying(20) NOT NULL
);


ALTER TABLE master.lc_casetype OWNER TO postgres;

--
-- Name: lc_hc_casetype; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.lc_hc_casetype (
    lccasecode bigint NOT NULL,
    lccasename character varying(100),
    corttyp character(1) DEFAULT 'L'::bpchar NOT NULL,
    display character(1) DEFAULT ''::bpchar NOT NULL,
    type_sname character varying(20) NOT NULL,
    case_type bigint NOT NULL,
    id bigint NOT NULL,
    is_deleted character varying(6) NOT NULL,
    ref_agency_state_id bigint NOT NULL,
    ref_agency_code_id bigint NOT NULL,
    cmis_state_id bigint NOT NULL,
    ent_user bigint,
    ent_time timestamp with time zone,
    ent_ip_address character varying(45),
    lccasename_hindi character varying(100)
);


ALTER TABLE master.lc_hc_casetype OWNER TO postgres;

--
-- Name: COLUMN lc_hc_casetype.case_type; Type: COMMENT; Schema: master; Owner: postgres
--

COMMENT ON COLUMN master.lc_hc_casetype.case_type IS 'DISTRICT COURT CASE CODE';


--
-- Name: lc_hc_casetype_lccasecode_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.lc_hc_casetype_lccasecode_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.lc_hc_casetype_lccasecode_seq OWNER TO postgres;

--
-- Name: lc_hc_casetype_lccasecode_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.lc_hc_casetype_lccasecode_seq OWNED BY master.lc_hc_casetype.lccasecode;


--
-- Name: listed_info; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.listed_info (
    id bigint NOT NULL,
    next_dt date,
    main_supp bigint,
    remark character varying(60),
    mainhead character(1),
    bench_flag character(1),
    fix_dt bigint,
    mentioning bigint,
    week_commencing bigint,
    freshly_filed bigint,
    freshly_filed_adj bigint,
    part_heard bigint,
    inperson bigint,
    bail bigint,
    after_week bigint,
    imp_ia bigint,
    ia bigint,
    nr_adj bigint,
    adm_order bigint,
    ordinary bigint,
    total bigint,
    usercode bigint,
    ent_dt character varying(45),
    roster_id bigint DEFAULT '0'::bigint
);


ALTER TABLE master.listed_info OWNER TO postgres;

--
-- Name: listed_info_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.listed_info_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.listed_info_id_seq OWNER TO postgres;

--
-- Name: listed_info_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.listed_info_id_seq OWNED BY master.listed_info.id;


--
-- Name: listing_purpose; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.listing_purpose (
    code bigint NOT NULL,
    purpose character varying(100) NOT NULL,
    priority bigint NOT NULL,
    displayable character varying(3) NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    transfer_pur character(1) NOT NULL,
    fx_wk character(1) DEFAULT 'W'::bpchar NOT NULL,
    purpose_hindi character varying(200)
);


ALTER TABLE master.listing_purpose OWNER TO postgres;

--
-- Name: m_court_fee; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.m_court_fee (
    id bigint NOT NULL,
    casetype_id bigint NOT NULL,
    submaster_id bigint NOT NULL,
    category_subcode bigint NOT NULL,
    category_subcode1 bigint NOT NULL,
    category_subcode2 bigint NOT NULL,
    case_law bigint NOT NULL,
    court_fee bigint NOT NULL,
    security_deposit bigint NOT NULL,
    flag character varying(1),
    display character varying(1) DEFAULT 'Y'::character varying,
    from_date date,
    to_date date,
    order_by bigint NOT NULL
);


ALTER TABLE master.m_court_fee OWNER TO postgres;

--
-- Name: m_court_fee_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.m_court_fee_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.m_court_fee_id_seq OWNER TO postgres;

--
-- Name: m_court_fee_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.m_court_fee_id_seq OWNED BY master.m_court_fee.id;


--
-- Name: m_court_fee_valuation; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.m_court_fee_valuation (
    id bigint NOT NULL,
    court_fee_id bigint NOT NULL,
    from_valuation bigint,
    to_valuation bigint NOT NULL,
    for_added_valuation bigint NOT NULL,
    added_amount bigint NOT NULL
);


ALTER TABLE master.m_court_fee_valuation OWNER TO postgres;

--
-- Name: m_court_fee_valuation_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.m_court_fee_valuation_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.m_court_fee_valuation_id_seq OWNER TO postgres;

--
-- Name: m_court_fee_valuation_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.m_court_fee_valuation_id_seq OWNED BY master.m_court_fee_valuation.id;


--
-- Name: m_from_court; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.m_from_court (
    id bigint NOT NULL,
    court_name character varying(50) NOT NULL,
    order_by bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    court_name_hindi character varying(50)
);


ALTER TABLE master.m_from_court OWNER TO postgres;

--
-- Name: m_from_court_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.m_from_court_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.m_from_court_id_seq OWNER TO postgres;

--
-- Name: m_from_court_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.m_from_court_id_seq OWNED BY master.m_from_court.id;


--
-- Name: m_limitation_period; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.m_limitation_period (
    id bigint NOT NULL,
    casetype_id bigint NOT NULL,
    submaster_id bigint NOT NULL,
    category_subcode bigint NOT NULL,
    category_subcode1 bigint NOT NULL,
    category_subcode2 bigint NOT NULL,
    case_law bigint NOT NULL,
    limitation bigint NOT NULL,
    order_cof character varying(1) NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    from_date date,
    to_date date,
    order_by bigint NOT NULL
);


ALTER TABLE master.m_limitation_period OWNER TO postgres;

--
-- Name: m_limitation_period_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.m_limitation_period_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.m_limitation_period_id_seq OWNER TO postgres;

--
-- Name: m_limitation_period_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.m_limitation_period_id_seq OWNED BY master.m_limitation_period.id;


--
-- Name: m_to_r_casetype_mapping; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.m_to_r_casetype_mapping (
    id bigint NOT NULL,
    m_casetype bigint NOT NULL,
    r_casetype bigint NOT NULL,
    display character(1) NOT NULL
);


ALTER TABLE master.m_to_r_casetype_mapping OWNER TO postgres;

--
-- Name: m_to_r_casetype_mapping_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.m_to_r_casetype_mapping_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.m_to_r_casetype_mapping_id_seq OWNER TO postgres;

--
-- Name: m_to_r_casetype_mapping_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.m_to_r_casetype_mapping_id_seq OWNED BY master.m_to_r_casetype_mapping.id;


--
-- Name: main_report; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.main_report (
    id bigint NOT NULL,
    "desc" character varying(100),
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE master.main_report OWNER TO postgres;

--
-- Name: main_report_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.main_report_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.main_report_id_seq OWNER TO postgres;

--
-- Name: main_report_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.main_report_id_seq OWNED BY master.main_report.id;


--
-- Name: master_banks; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.master_banks (
    id bigint NOT NULL,
    bank_name text,
    updated_by character varying(30),
    updated_datetime timestamp with time zone,
    contact_person character varying(45),
    email_id character varying(45),
    ph_no bigint
);


ALTER TABLE master.master_banks OWNER TO postgres;

--
-- Name: master_banks_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.master_banks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.master_banks_id_seq OWNER TO postgres;

--
-- Name: master_banks_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.master_banks_id_seq OWNED BY master.master_banks.id;


--
-- Name: master_bench; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.master_bench (
    id smallint NOT NULL,
    bench_name character varying(20) NOT NULL,
    abbr character varying(10) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    board_type_mb character varying(10)
);


ALTER TABLE master.master_bench OWNER TO postgres;

--
-- Name: master_board_type; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.master_board_type (
    board_id character varying(1) NOT NULL,
    board_display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    board_name character varying(50) NOT NULL
);


ALTER TABLE master.master_board_type OWNER TO postgres;

--
-- Name: master_case_status; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.master_case_status (
    id bigint NOT NULL,
    status_code character(1),
    description character varying(50),
    entuser bigint NOT NULL,
    entdt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    flag_pd character(1) NOT NULL
);


ALTER TABLE master.master_case_status OWNER TO postgres;

--
-- Name: master_court_complex; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.master_court_complex (
    id bigint NOT NULL,
    state_code bigint NOT NULL,
    district_code bigint NOT NULL,
    court_name character varying(100) NOT NULL
);


ALTER TABLE master.master_court_complex OWNER TO postgres;

--
-- Name: master_fdstatus; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.master_fdstatus (
    id bigint NOT NULL,
    status text
);


ALTER TABLE master.master_fdstatus OWNER TO postgres;

--
-- Name: master_fdstatus_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.master_fdstatus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.master_fdstatus_id_seq OWNER TO postgres;

--
-- Name: master_fdstatus_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.master_fdstatus_id_seq OWNED BY master.master_fdstatus.id;


--
-- Name: master_fixedfor; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.master_fixedfor (
    id bigint NOT NULL,
    fixed_for_desc character varying(60),
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    displayat character varying(10) NOT NULL,
    oldcode character(1) NOT NULL
);


ALTER TABLE master.master_fixedfor OWNER TO postgres;

--
-- Name: master_fixedfor_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.master_fixedfor_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.master_fixedfor_id_seq OWNER TO postgres;

--
-- Name: master_fixedfor_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.master_fixedfor_id_seq OWNED BY master.master_fixedfor.id;


--
-- Name: master_list_type; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.master_list_type (
    id bigint NOT NULL,
    list_type_name character varying(45) NOT NULL
);


ALTER TABLE master.master_list_type OWNER TO postgres;

--
-- Name: master_list_type_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.master_list_type_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.master_list_type_id_seq OWNER TO postgres;

--
-- Name: master_list_type_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.master_list_type_id_seq OWNED BY master.master_list_type.id;


--
-- Name: master_main_supp; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.master_main_supp (
    id bigint DEFAULT 0 NOT NULL,
    descrip character varying(30) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE master.master_main_supp OWNER TO postgres;

--
-- Name: master_module; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.master_module (
    id bigint NOT NULL,
    module_name character varying(30) NOT NULL,
    module_desc character varying(30) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE master.master_module OWNER TO postgres;

--
-- Name: master_module_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.master_module_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.master_module_id_seq OWNER TO postgres;

--
-- Name: master_module_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.master_module_id_seq OWNED BY master.master_module.id;


--
-- Name: master_stakeholder_type; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.master_stakeholder_type (
    id bigint NOT NULL,
    description character varying(222),
    is_active boolean DEFAULT true,
    created_on timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE master.master_stakeholder_type OWNER TO postgres;

--
-- Name: master_stakeholder_type_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.master_stakeholder_type_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.master_stakeholder_type_id_seq OWNER TO postgres;

--
-- Name: master_stakeholder_type_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.master_stakeholder_type_id_seq OWNED BY master.master_stakeholder_type.id;


--
-- Name: media_persions; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.media_persions (
    id bigint NOT NULL,
    name character varying(200) DEFAULT 'NA'::character varying NOT NULL,
    media_name character varying(400) DEFAULT 'NA'::character varying NOT NULL,
    mobile character varying(10) NOT NULL,
    otp bigint DEFAULT 0,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    create_on timestamp with time zone,
    last_login timestamp with time zone
);


ALTER TABLE master.media_persions OWNER TO postgres;

--
-- Name: media_persions_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.media_persions_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.media_persions_id_seq OWNER TO postgres;

--
-- Name: media_persions_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.media_persions_id_seq OWNED BY master.media_persions.id;


--
-- Name: menu; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.menu (
    id bigint NOT NULL,
    menu_nm character varying(500),
    priority bigint,
    display character varying(1),
    menu_id character varying(12),
    url character varying(200),
    old_smenu_id bigint NOT NULL,
    icon character varying(45),
    create_modify timestamp without time zone,
    updated_on timestamp without time zone,
    updated_by bigint,
    updated_by_ip character varying(20)
);


ALTER TABLE master.menu OWNER TO postgres;

--
-- Name: menu_for_latestupdates; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.menu_for_latestupdates (
    mno bigint NOT NULL,
    menu_name character varying(50) NOT NULL,
    folder_name character varying(20) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE master.menu_for_latestupdates OWNER TO postgres;

--
-- Name: menu_for_latestupdates_mno_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.menu_for_latestupdates_mno_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.menu_for_latestupdates_mno_seq OWNER TO postgres;

--
-- Name: menu_for_latestupdates_mno_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.menu_for_latestupdates_mno_seq OWNED BY master.menu_for_latestupdates.mno;


--
-- Name: menu_old; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.menu_old (
    id bigint NOT NULL,
    menu_nm character varying(500) NOT NULL,
    priority bigint,
    display character varying(1) DEFAULT 'Y'::character varying,
    menu_id character varying(12),
    url character varying(200) DEFAULT '#'::character varying,
    old_smenu_id bigint DEFAULT '0'::bigint NOT NULL,
    icon character varying(45) DEFAULT 'null'::character varying
);


ALTER TABLE master.menu_old OWNER TO postgres;

--
-- Name: menu_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.menu_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.menu_id_seq OWNER TO postgres;

--
-- Name: menu_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.menu_id_seq OWNED BY master.menu_old.id;


--
-- Name: menu_id_seq1; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.menu_id_seq1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.menu_id_seq1 OWNER TO postgres;

--
-- Name: menu_id_seq1; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.menu_id_seq1 OWNED BY master.menu.id;


--
-- Name: mn_me_per; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.mn_me_per (
    id bigint NOT NULL,
    us_code bigint NOT NULL,
    mn_me_per bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying
);


ALTER TABLE master.mn_me_per OWNER TO postgres;

--
-- Name: mn_me_per_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.mn_me_per_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.mn_me_per_id_seq OWNER TO postgres;

--
-- Name: mn_me_per_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.mn_me_per_id_seq OWNED BY master.mn_me_per.id;


--
-- Name: module_table; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.module_table (
    id bigint NOT NULL,
    module_name character varying(222),
    created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE master.module_table OWNER TO postgres;

--
-- Name: module_table_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.module_table_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.module_table_id_seq OWNER TO postgres;

--
-- Name: module_table_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.module_table_id_seq OWNED BY master.module_table.id;


--
-- Name: national_case_type_revised; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.national_case_type_revised (
    national_code bigint,
    case_type bigint,
    type_name text,
    ci_cr bigint,
    short_name text,
    relief_type text,
    relief_code bigint,
    type_of_jurisdiction text
);


ALTER TABLE master.national_case_type_revised OWNER TO postgres;

--
-- Name: national_code_for_acts; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.national_code_for_acts (
    id bigint NOT NULL,
    act_name character varying(200),
    national_code character varying(45)
);


ALTER TABLE master.national_code_for_acts OWNER TO postgres;

--
-- Name: national_code_judge; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.national_code_judge (
    judge_code bigint NOT NULL,
    judge_name text,
    short_judge_name text,
    uid text,
    judge_priority text,
    desg_code bigint
);


ALTER TABLE master.national_code_judge OWNER TO postgres;

--
-- Name: national_disposal_type; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.national_disposal_type (
    disp_type bigint NOT NULL,
    disp_name character varying(100),
    short_name character varying(100),
    national_code bigint,
    ndisposal_type character varying(200)
);


ALTER TABLE master.national_disposal_type OWNER TO postgres;

--
-- Name: national_purpose_listing_stage; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.national_purpose_listing_stage (
    purpose_name text,
    purpose_code bigint NOT NULL,
    national_code bigint,
    short_name text
);


ALTER TABLE master.national_purpose_listing_stage OWNER TO postgres;

--
-- Name: not_before_reason; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.not_before_reason (
    res_id bigint NOT NULL,
    notbef character(1) NOT NULL,
    res_add character varying(30) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE master.not_before_reason OWNER TO postgres;

--
-- Name: not_before_reason_res_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.not_before_reason_res_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.not_before_reason_res_id_seq OWNER TO postgres;

--
-- Name: not_before_reason_res_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.not_before_reason_res_id_seq OWNED BY master.not_before_reason.res_id;


--
-- Name: notice_mapping; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.notice_mapping (
    id bigint NOT NULL,
    section_id bigint,
    registrar bigint,
    additional_registrar character varying(50),
    deputy_registrar character varying(50),
    assistant_registrar character varying(50),
    branch_officer character varying(50)
);


ALTER TABLE master.notice_mapping OWNER TO postgres;

--
-- Name: notice_mapping_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.notice_mapping_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.notice_mapping_id_seq OWNER TO postgres;

--
-- Name: notice_mapping_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.notice_mapping_id_seq OWNED BY master.notice_mapping.id;


--
-- Name: ntl_judge; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ntl_judge (
    org_advocate_id bigint,
    org_judge_id bigint,
    userid bigint NOT NULL,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    del_dt timestamp with time zone,
    del_user bigint NOT NULL
);


ALTER TABLE master.ntl_judge OWNER TO postgres;

--
-- Name: ntl_judge_category; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ntl_judge_category (
    cat_id bigint,
    org_judge_id bigint,
    userid bigint NOT NULL,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE master.ntl_judge_category OWNER TO postgres;

--
-- Name: ntl_judge_dept; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ntl_judge_dept (
    dept_id bigint,
    org_judge_id bigint,
    userid bigint NOT NULL,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    del_dt timestamp with time zone,
    del_user bigint NOT NULL
);


ALTER TABLE master.ntl_judge_dept OWNER TO postgres;

--
-- Name: objection; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.objection (
    objcode bigint NOT NULL,
    defect_code_main character(3),
    objdesc text,
    description_old text,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    defect_code_sub character varying(10),
    is_deleted character varying(5) NOT NULL,
    defect_code_display bigint,
    other_info character(1),
    description_display text,
    display character(1) NOT NULL,
    sideflg character(2) NOT NULL,
    objdesc_hindi text,
    mul_ent character varying
);


ALTER TABLE master.objection OWNER TO postgres;

--
-- Name: objection_objcode_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.objection_objcode_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.objection_objcode_seq OWNER TO postgres;

--
-- Name: objection_objcode_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.objection_objcode_seq OWNED BY master.objection.objcode;


--
-- Name: occupation_type; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.occupation_type (
    id bigint NOT NULL,
    occ_desc character varying(50) NOT NULL,
    display public.occupation_type_display DEFAULT 'Y'::public.occupation_type_display NOT NULL,
    entdt timestamp with time zone,
    entuser bigint NOT NULL,
    updt timestamp with time zone,
    upuser bigint NOT NULL
);


ALTER TABLE master.occupation_type OWNER TO postgres;

--
-- Name: occupation_type_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.occupation_type_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.occupation_type_id_seq OWNER TO postgres;

--
-- Name: occupation_type_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.occupation_type_id_seq OWNED BY master.occupation_type.id;


--
-- Name: office_report_master; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.office_report_master (
    id bigint NOT NULL,
    case_nature character varying(1) NOT NULL,
    r_nature character varying(50) NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying
);


ALTER TABLE master.office_report_master OWNER TO postgres;

--
-- Name: office_report_master_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.office_report_master_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.office_report_master_id_seq OWNER TO postgres;

--
-- Name: office_report_master_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.office_report_master_id_seq OWNED BY master.office_report_master.id;


--
-- Name: org_lower_court_judges; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.org_lower_court_judges (
    id bigint NOT NULL,
    judge_code bigint,
    abbreviation character(15),
    title character varying(15),
    first_name character varying(40),
    sur_name character varying(40),
    appointment_date timestamp with time zone,
    retirement_date timestamp with time zone,
    is_retired character varying(5),
    reg_agency_state_id bigint,
    updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted character varying(5) NOT NULL,
    cmis_state_id bigint NOT NULL,
    supreme_court_jud_id bigint NOT NULL,
    ent_ip_address character varying(45)
);


ALTER TABLE master.org_lower_court_judges OWNER TO postgres;

--
-- Name: org_lower_court_judges_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.org_lower_court_judges_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.org_lower_court_judges_id_seq OWNER TO postgres;

--
-- Name: org_lower_court_judges_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.org_lower_court_judges_id_seq OWNED BY master.org_lower_court_judges.id;


--
-- Name: page_charges; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.page_charges (
    ordinary_charge bigint,
    express_charge bigint,
    from_dt date,
    to_dt date
);


ALTER TABLE master.page_charges OWNER TO postgres;

--
-- Name: pending_type; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.pending_type (
    id bigint NOT NULL,
    status_code character(1),
    description character varying(50),
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted character varying(5) NOT NULL
);


ALTER TABLE master.pending_type OWNER TO postgres;

--
-- Name: police; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.police (
    policestncd bigint DEFAULT 0 NOT NULL,
    policestndesc character varying(100),
    display character(1) DEFAULT 'Y'::bpchar,
    cmis_state_id bigint NOT NULL,
    cmis_district_id bigint NOT NULL,
    ent_user bigint,
    ent_time timestamp with time zone,
    ent_ip_address character varying(45)
);


ALTER TABLE master.police OWNER TO postgres;

--
-- Name: post_distance_master; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.post_distance_master (
    post_office_id character varying(50),
    district_name character varying(100),
    taluk_name character varying(100),
    post_office_name character varying(100),
    pincode bigint NOT NULL,
    distance_from_sci numeric(15,2),
    is_local character(1) DEFAULT 'N'::bpchar NOT NULL,
    state character varying(100)
);


ALTER TABLE master.post_distance_master OWNER TO postgres;

--
-- Name: post_envelop_master; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.post_envelop_master (
    id bigint NOT NULL,
    envelop_type character varying(45),
    max_pages_limit character varying(45),
    envelop_weight bigint,
    glue_pinup_weight bigint,
    display character(1) DEFAULT 'Y'::bpchar,
    from_date date,
    to_date date,
    entry_time timestamp with time zone,
    usercode bigint
);


ALTER TABLE master.post_envelop_master OWNER TO postgres;

--
-- Name: post_envelop_master_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.post_envelop_master_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.post_envelop_master_id_seq OWNER TO postgres;

--
-- Name: post_envelop_master_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.post_envelop_master_id_seq OWNED BY master.post_envelop_master.id;


--
-- Name: post_t; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.post_t (
    post_code bigint DEFAULT '0'::bigint NOT NULL,
    post_name character varying(150) DEFAULT ''::character varying NOT NULL,
    cadre_code bigint DEFAULT '0'::bigint NOT NULL,
    desig_no bigint DEFAULT '0'::bigint NOT NULL,
    status character(1),
    deputation character(1) DEFAULT ''::bpchar NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    funds smallint NOT NULL,
    sq bigint NOT NULL,
    abbr character varying(150),
    oldcadre_code character varying(10) DEFAULT '0'::character varying NOT NULL,
    ent_user bigint,
    ent_time timestamp with time zone,
    ent_ip_address character varying(45)
);


ALTER TABLE master.post_t OWNER TO postgres;

--
-- Name: post_tariff_calc_master; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.post_tariff_calc_master (
    id bigint NOT NULL,
    distance_from bigint NOT NULL,
    distance_to bigint NOT NULL,
    weight_type character varying(45) NOT NULL,
    weight_from bigint NOT NULL,
    weight_to bigint NOT NULL,
    rate character varying(45),
    from_date date,
    to_date date,
    tax numeric(15,2) DEFAULT 18.00 NOT NULL
);


ALTER TABLE master.post_tariff_calc_master OWNER TO postgres;

--
-- Name: post_tariff_calc_master_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.post_tariff_calc_master_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.post_tariff_calc_master_id_seq OWNER TO postgres;

--
-- Name: post_tariff_calc_master_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.post_tariff_calc_master_id_seq OWNED BY master.post_tariff_calc_master.id;


--
-- Name: random_user; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.random_user (
    id bigint NOT NULL,
    empid character varying(70),
    ent_date date
);


ALTER TABLE master.random_user OWNER TO postgres;

--
-- Name: random_user_hc; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.random_user_hc (
    id bigint NOT NULL,
    empid character varying(70),
    ent_date date,
    create_modify timestamp without time zone,
    updated_by bigint,
    updated_by_ip text
);


ALTER TABLE master.random_user_hc OWNER TO postgres;

--
-- Name: random_user_hc_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.random_user_hc_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.random_user_hc_id_seq OWNER TO postgres;

--
-- Name: random_user_hc_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.random_user_hc_id_seq OWNED BY master.random_user_hc.id;


--
-- Name: random_user_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.random_user_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.random_user_id_seq OWNER TO postgres;

--
-- Name: random_user_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.random_user_id_seq OWNED BY master.random_user.id;


--
-- Name: ref_agency_code; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_agency_code (
    id bigint NOT NULL,
    agency_name character varying(100) NOT NULL,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    state_id bigint NOT NULL,
    agency_or_court character varying(1) NOT NULL,
    short_agency_name character varying(8) NOT NULL,
    is_deleted character varying(4),
    is_main character varying(4),
    head_post character varying(100) NOT NULL,
    address character varying(500) NOT NULL,
    ref_city_id bigint NOT NULL,
    cmis_state_id bigint NOT NULL,
    district_no bigint,
    main_branch bigint,
    ent_ip_address character varying(45),
    agency_name_hindi character varying(100)
);


ALTER TABLE master.ref_agency_code OWNER TO postgres;

--
-- Name: ref_agency_code_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_agency_code_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_agency_code_id_seq OWNER TO postgres;

--
-- Name: ref_agency_code_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_agency_code_id_seq OWNED BY master.ref_agency_code.id;


--
-- Name: ref_agency_state; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_agency_state (
    id bigint NOT NULL,
    agency_state character varying(30) NOT NULL,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted character varying(6) NOT NULL,
    agency_state_code character varying(2) NOT NULL,
    cmis_state_id bigint NOT NULL,
    agency_state_hindi character varying(45)
);


ALTER TABLE master.ref_agency_state OWNER TO postgres;

--
-- Name: ref_agency_state_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_agency_state_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_agency_state_id_seq OWNER TO postgres;

--
-- Name: ref_agency_state_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_agency_state_id_seq OWNED BY master.ref_agency_state.id;


--
-- Name: ref_city; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_city (
    id bigint,
    city_code bigint,
    city_name text,
    ref_district_id text,
    ref_state_id bigint,
    is_deleted text,
    adm_updated_by bigint,
    updated_on text
);


ALTER TABLE master.ref_city OWNER TO postgres;

--
-- Name: ref_copying_source; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_copying_source (
    id bigint NOT NULL,
    description character varying(45),
    adm_updated_by bigint,
    updated_on timestamp with time zone,
    is_deleted character varying(2) DEFAULT 'f'::character varying
);


ALTER TABLE master.ref_copying_source OWNER TO postgres;

--
-- Name: ref_copying_source_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_copying_source_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_copying_source_id_seq OWNER TO postgres;

--
-- Name: ref_copying_source_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_copying_source_id_seq OWNED BY master.ref_copying_source.id;


--
-- Name: ref_copying_status; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_copying_status (
    id bigint NOT NULL,
    status_code character varying(45),
    status_description character varying(45),
    updated_by bigint,
    updated_on timestamp with time zone,
    is_deleted character varying(1) DEFAULT 'f'::character varying
);


ALTER TABLE master.ref_copying_status OWNER TO postgres;

--
-- Name: ref_copying_status_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_copying_status_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_copying_status_id_seq OWNER TO postgres;

--
-- Name: ref_copying_status_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_copying_status_id_seq OWNED BY master.ref_copying_status.id;


--
-- Name: ref_defect_code; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_defect_code (
    id bigint NOT NULL,
    defect_code_main character(3),
    description text,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    defect_code_sub character varying(10),
    is_deleted character varying(5) NOT NULL,
    defect_code_display bigint,
    other_info character(1),
    description_display text
);


ALTER TABLE master.ref_defect_code OWNER TO postgres;

--
-- Name: ref_faster_steps; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_faster_steps (
    id bigint NOT NULL,
    description character varying(100) NOT NULL,
    created_on timestamp with time zone,
    created_by bigint NOT NULL,
    is_deleted smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE master.ref_faster_steps OWNER TO postgres;

--
-- Name: ref_faster_steps_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_faster_steps_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_faster_steps_id_seq OWNER TO postgres;

--
-- Name: ref_faster_steps_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_faster_steps_id_seq OWNED BY master.ref_faster_steps.id;


--
-- Name: ref_file_movement_status; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_file_movement_status (
    id bigint NOT NULL,
    movement_status character varying(100),
    updated_on timestamp with time zone,
    usercode bigint
);


ALTER TABLE master.ref_file_movement_status OWNER TO postgres;

--
-- Name: ref_file_movement_status_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_file_movement_status_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_file_movement_status_id_seq OWNER TO postgres;

--
-- Name: ref_file_movement_status_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_file_movement_status_id_seq OWNED BY master.ref_file_movement_status.id;


--
-- Name: ref_items; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_items (
    id bigint NOT NULL,
    item_name character varying(200) NOT NULL,
    is_deleted character(1) DEFAULT 'f'::bpchar NOT NULL,
    last_updated_on timestamp with time zone,
    last_updated_by bigint NOT NULL
);


ALTER TABLE master.ref_items OWNER TO postgres;

--
-- Name: ref_items_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_items_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_items_id_seq OWNER TO postgres;

--
-- Name: ref_items_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_items_id_seq OWNED BY master.ref_items.id;


--
-- Name: ref_keyword; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_keyword (
    id bigint NOT NULL,
    keyword_code bigint NOT NULL,
    keyword_description character varying(500) NOT NULL,
    updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted character varying(1) NOT NULL
);


ALTER TABLE master.ref_keyword OWNER TO postgres;

--
-- Name: ref_keyword_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_keyword_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_keyword_id_seq OWNER TO postgres;

--
-- Name: ref_keyword_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_keyword_id_seq OWNED BY master.ref_keyword.id;


--
-- Name: ref_letter_status; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_letter_status (
    id bigint NOT NULL,
    description character varying(100),
    display character(1) DEFAULT 'Y'::bpchar,
    usercode bigint,
    updated_on timestamp with time zone
);


ALTER TABLE master.ref_letter_status OWNER TO postgres;

--
-- Name: ref_letter_status_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_letter_status_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_letter_status_id_seq OWNER TO postgres;

--
-- Name: ref_letter_status_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_letter_status_id_seq OWNED BY master.ref_letter_status.id;


--
-- Name: ref_lower_court_case_type; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_lower_court_case_type (
    lccasecode bigint DEFAULT 0 NOT NULL,
    lccasename character varying(100),
    corttyp character(1) DEFAULT 'L'::bpchar NOT NULL,
    display character(1) DEFAULT ''::bpchar NOT NULL,
    type_sname character varying(15) NOT NULL,
    case_type bigint NOT NULL,
    id bigint NOT NULL,
    is_deleted character varying(6) NOT NULL,
    ref_agency_state_id bigint NOT NULL,
    ref_agency_code_id bigint NOT NULL
);


ALTER TABLE master.ref_lower_court_case_type OWNER TO postgres;

--
-- Name: COLUMN ref_lower_court_case_type.type_sname; Type: COMMENT; Schema: master; Owner: postgres
--

COMMENT ON COLUMN master.ref_lower_court_case_type.type_sname IS 'DISTRICT COURT SHORT NAME';


--
-- Name: COLUMN ref_lower_court_case_type.case_type; Type: COMMENT; Schema: master; Owner: postgres
--

COMMENT ON COLUMN master.ref_lower_court_case_type.case_type IS 'DISTRICT COURT CASE CODE';


--
-- Name: ref_order_defect; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_order_defect (
    id bigint NOT NULL,
    code bigint,
    description text,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted character varying(5) NOT NULL
);


ALTER TABLE master.ref_order_defect OWNER TO postgres;

--
-- Name: ref_order_type; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_order_type (
    id bigint NOT NULL,
    order_type text,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted character varying(5) NOT NULL,
    is_for_proceedings character varying(5) NOT NULL,
    is_for_decree character varying(5) NOT NULL,
    is_for_notice character varying(5) NOT NULL,
    mandate_date_of_order_type character(1) DEFAULT 'Y'::bpchar NOT NULL,
    mandate_remark_of_order_type character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE master.ref_order_type OWNER TO postgres;

--
-- Name: ref_pil_action_taken; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_pil_action_taken (
    id bigint NOT NULL,
    action_code text,
    pil_sub_action_code text,
    sub_action_description text,
    is_deleted character(1) DEFAULT 'f'::bpchar,
    adm_updated_by bigint,
    updated_on timestamp with time zone
);


ALTER TABLE master.ref_pil_action_taken OWNER TO postgres;

--
-- Name: ref_pil_action_taken_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_pil_action_taken_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_pil_action_taken_id_seq OWNER TO postgres;

--
-- Name: ref_pil_action_taken_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_pil_action_taken_id_seq OWNED BY master.ref_pil_action_taken.id;


--
-- Name: ref_pil_category; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_pil_category (
    id bigint NOT NULL,
    pil_code bigint,
    pil_category text,
    is_deleted character(1) DEFAULT 'f'::bpchar,
    adm_updated_by bigint,
    updated_on timestamp with time zone,
    pil_type text
);


ALTER TABLE master.ref_pil_category OWNER TO postgres;

--
-- Name: ref_pil_category_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_pil_category_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_pil_category_id_seq OWNER TO postgres;

--
-- Name: ref_pil_category_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_pil_category_id_seq OWNED BY master.ref_pil_category.id;


--
-- Name: ref_postal_type; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_postal_type (
    id bigint NOT NULL,
    postal_type_code bigint,
    postal_type_description character varying(100),
    updated_on timestamp(6) with time zone,
    adm_updated_by bigint NOT NULL,
    is_deleted character varying(5) NOT NULL
);


ALTER TABLE master.ref_postal_type OWNER TO postgres;

--
-- Name: ref_rr_hall; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_rr_hall (
    hall_no bigint NOT NULL,
    description character varying(200),
    display character(1) DEFAULT 'T'::bpchar,
    updated_by bigint,
    updated_on timestamp with time zone
);


ALTER TABLE master.ref_rr_hall OWNER TO postgres;

--
-- Name: ref_rr_hall_hall_no_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_rr_hall_hall_no_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_rr_hall_hall_no_seq OWNER TO postgres;

--
-- Name: ref_rr_hall_hall_no_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_rr_hall_hall_no_seq OWNED BY master.ref_rr_hall.hall_no;


--
-- Name: ref_special_category_filing; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_special_category_filing (
    id bigint NOT NULL,
    category_name character varying(200),
    display character varying(2),
    updated_by bigint,
    updated_on timestamp with time zone
);


ALTER TABLE master.ref_special_category_filing OWNER TO postgres;

--
-- Name: ref_special_category_filing_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_special_category_filing_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_special_category_filing_id_seq OWNER TO postgres;

--
-- Name: ref_special_category_filing_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_special_category_filing_id_seq OWNED BY master.ref_special_category_filing.id;


--
-- Name: ref_state; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_state (
    id bigint NOT NULL,
    state_name text,
    adm_updated_by bigint,
    updated_on timestamp with time zone,
    short_name text,
    is_deleted character(1) DEFAULT 'f'::bpchar,
    state_code text,
    barcouncil_emailid text,
    state_name_hindi text
);


ALTER TABLE master.ref_state OWNER TO postgres;

--
-- Name: ref_state_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.ref_state_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.ref_state_id_seq OWNER TO postgres;

--
-- Name: ref_state_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.ref_state_id_seq OWNED BY master.ref_state.id;


--
-- Name: ref_subject_category; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.ref_subject_category (
    id bigint,
    subject_sub_category_code character varying(6),
    subject_subcategory_description character varying(250),
    subject_category_code character varying(2),
    subject_category_description character varying(250),
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted character varying(6) DEFAULT '0'::character varying,
    is_heavy character varying(6) DEFAULT '0'::character varying
);


ALTER TABLE master.ref_subject_category OWNER TO postgres;

--
-- Name: role_master; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.role_master (
    id bigint NOT NULL,
    role_desc character varying(45),
    display character(1) DEFAULT 'Y'::bpchar,
    updated_by bigint,
    updated_on timestamp with time zone
);


ALTER TABLE master.role_master OWNER TO postgres;

--
-- Name: role_master_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.role_master_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.role_master_id_seq OWNER TO postgres;

--
-- Name: role_master_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.role_master_id_seq OWNED BY master.role_master.id;


--
-- Name: role_menu_mapping; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.role_menu_mapping (
    id bigint NOT NULL,
    role_master_id bigint,
    menu_id character varying(50),
    display character(1) DEFAULT 'Y'::bpchar,
    updated_by bigint,
    updated_on timestamp with time zone
);


ALTER TABLE master.role_menu_mapping OWNER TO postgres;

--
-- Name: COLUMN role_menu_mapping.menu_id; Type: COMMENT; Schema: master; Owner: postgres
--

COMMENT ON COLUMN master.role_menu_mapping.menu_id IS 'id column of menu table';


--
-- Name: role_menu_mapping_history; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.role_menu_mapping_history (
    id bigint DEFAULT '0'::bigint NOT NULL,
    role_master_id bigint,
    menu_id text,
    display character(1) DEFAULT 'Y'::bpchar,
    updated_by bigint,
    updated_on timestamp with time zone
);


ALTER TABLE master.role_menu_mapping_history OWNER TO postgres;

--
-- Name: COLUMN role_menu_mapping_history.menu_id; Type: COMMENT; Schema: master; Owner: postgres
--

COMMENT ON COLUMN master.role_menu_mapping_history.menu_id IS 'id column of menu table';


--
-- Name: role_menu_mapping_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.role_menu_mapping_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.role_menu_mapping_id_seq OWNER TO postgres;

--
-- Name: role_menu_mapping_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.role_menu_mapping_id_seq OWNED BY master.role_menu_mapping.id;


--
-- Name: roster; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.roster (
    id bigint NOT NULL,
    bench_id bigint NOT NULL,
    from_date date,
    to_date date,
    entry_dt date,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    courtno bigint DEFAULT 0 NOT NULL,
    m_f character varying(5) NOT NULL,
    frm_time character varying(10) NOT NULL,
    tot_cases bigint NOT NULL,
    session character varying(20) NOT NULL,
    judges character varying(100) NOT NULL,
    if_print_in smallint DEFAULT '0'::smallint
);


ALTER TABLE master.roster OWNER TO postgres;

--
-- Name: roster_bench; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.roster_bench (
    id bigint NOT NULL,
    bench_id bigint NOT NULL,
    bench_no character varying(30),
    display character varying(2) DEFAULT 'Y'::character varying NOT NULL,
    priority bigint NOT NULL
);


ALTER TABLE master.roster_bench OWNER TO postgres;

--
-- Name: roster_bench_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.roster_bench_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.roster_bench_id_seq OWNER TO postgres;

--
-- Name: roster_bench_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.roster_bench_id_seq OWNED BY master.roster_bench.id;


--
-- Name: roster_judge; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.roster_judge (
    id bigint NOT NULL,
    roster_id bigint NOT NULL,
    judge_id bigint,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE master.roster_judge OWNER TO postgres;

--
-- Name: roster_judge_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.roster_judge_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.roster_judge_id_seq OWNER TO postgres;

--
-- Name: roster_judge_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.roster_judge_id_seq OWNED BY master.roster_judge.id;


--
-- Name: rr_da_case_distribution; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.rr_da_case_distribution (
    id bigint NOT NULL,
    user_code bigint NOT NULL,
    case_from bigint NOT NULL,
    case_to bigint NOT NULL,
    caseyear_from bigint,
    caseyear_to bigint,
    casehead character(1) NOT NULL,
    casetype bigint,
    valid_from timestamp with time zone,
    valid_to timestamp with time zone,
    updated_by bigint,
    update_on timestamp with time zone,
    display character(1)
);


ALTER TABLE master.rr_da_case_distribution OWNER TO postgres;

--
-- Name: rr_da_case_distribution_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.rr_da_case_distribution_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.rr_da_case_distribution_id_seq OWNER TO postgres;

--
-- Name: rr_da_case_distribution_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.rr_da_case_distribution_id_seq OWNED BY master.rr_da_case_distribution.id;


--
-- Name: rr_hall_case_distribution; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.rr_hall_case_distribution (
    id bigint NOT NULL,
    hall_no bigint NOT NULL,
    case_from bigint NOT NULL,
    case_to bigint NOT NULL,
    caseyear_from bigint,
    caseyear_to bigint,
    casetype bigint,
    valid_from timestamp with time zone,
    valid_to timestamp with time zone,
    updated_by bigint,
    update_on timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar,
    case_head character(1),
    is_diary_stage character(1) DEFAULT 'N'::bpchar
);


ALTER TABLE master.rr_hall_case_distribution OWNER TO postgres;

--
-- Name: rr_hall_case_distribution_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.rr_hall_case_distribution_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.rr_hall_case_distribution_id_seq OWNER TO postgres;

--
-- Name: rr_hall_case_distribution_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.rr_hall_case_distribution_id_seq OWNED BY master.rr_hall_case_distribution.id;


--
-- Name: rr_user_hall_mapping; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.rr_user_hall_mapping (
    id bigint NOT NULL,
    usercode bigint NOT NULL,
    ref_hall_no character varying(45) NOT NULL,
    from_date timestamp with time zone,
    to_date timestamp with time zone,
    display character(1) DEFAULT 'T'::bpchar,
    update_on timestamp with time zone,
    updated_by bigint
);


ALTER TABLE master.rr_user_hall_mapping OWNER TO postgres;

--
-- Name: rr_user_hall_mapping_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.rr_user_hall_mapping_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.rr_user_hall_mapping_id_seq OWNER TO postgres;

--
-- Name: rr_user_hall_mapping_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.rr_user_hall_mapping_id_seq OWNED BY master.rr_user_hall_mapping.id;


--
-- Name: rto; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.rto (
    id bigint NOT NULL,
    state bigint,
    district bigint,
    code character varying(5) NOT NULL,
    rto_name character varying(100) NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL
);


ALTER TABLE master.rto OWNER TO postgres;

--
-- Name: rto_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.rto_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.rto_id_seq OWNER TO postgres;

--
-- Name: rto_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.rto_id_seq OWNED BY master.rto.id;


--
-- Name: sc_working_days; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.sc_working_days (
    id bigint NOT NULL,
    working_date date,
    is_nmd smallint DEFAULT '0'::smallint NOT NULL,
    is_holiday smallint DEFAULT '0'::smallint NOT NULL,
    holiday_description character varying(200),
    updated_by bigint DEFAULT '1'::bigint NOT NULL,
    updated_on timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    misc_dt date,
    nmd_dt date,
    sec_list_dt date,
    misc_dt1 date,
    misc_dt2 date,
    holiday_for_registry smallint
);


ALTER TABLE master.sc_working_days OWNER TO postgres;

--
-- Name: sc_working_days_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.sc_working_days_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.sc_working_days_id_seq OWNER TO postgres;

--
-- Name: sc_working_days_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.sc_working_days_id_seq OWNED BY master.sc_working_days.id;


--
-- Name: sensitive_case_users; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.sensitive_case_users (
    id bigint NOT NULL,
    users_empid character varying(1000) NOT NULL
);


ALTER TABLE master.sensitive_case_users OWNER TO postgres;

--
-- Name: sensitive_case_users_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.sensitive_case_users_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.sensitive_case_users_id_seq OWNER TO postgres;

--
-- Name: sensitive_case_users_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.sensitive_case_users_id_seq OWNED BY master.sensitive_case_users.id;


--
-- Name: similarity_remarks; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.similarity_remarks (
    id bigint NOT NULL,
    conditions character varying(100),
    remarks character varying(500),
    ent_on timestamp with time zone,
    ent_by character varying(100),
    modified_on timestamp with time zone,
    modified_by character varying(45),
    display character(1)
);


ALTER TABLE master.similarity_remarks OWNER TO postgres;

--
-- Name: similarity_remarks_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.similarity_remarks_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.similarity_remarks_id_seq OWNER TO postgres;

--
-- Name: similarity_remarks_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.similarity_remarks_id_seq OWNED BY master.similarity_remarks.id;


--
-- Name: single_judge_nominate; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.single_judge_nominate (
    id bigint NOT NULL,
    jcode bigint NOT NULL,
    day_type character varying(20) NOT NULL,
    from_date date,
    to_date date,
    entry_date timestamp with time zone,
    usercode bigint NOT NULL,
    is_active smallint DEFAULT '1'::smallint NOT NULL,
    updated_on timestamp with time zone,
    update_by bigint,
    delete_reason character varying(100)
);


ALTER TABLE master.single_judge_nominate OWNER TO postgres;

--
-- Name: single_judge_nominate_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.single_judge_nominate_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.single_judge_nominate_id_seq OWNER TO postgres;

--
-- Name: single_judge_nominate_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.single_judge_nominate_id_seq OWNED BY master.single_judge_nominate.id;


--
-- Name: sitting_plan_court_details; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.sitting_plan_court_details (
    id bigint NOT NULL,
    sitting_plan_details_id bigint,
    court_number bigint,
    board_type character(2),
    if_special_bench smallint DEFAULT '0'::smallint,
    header_remark character varying(45),
    footer_remark character varying(45),
    mainhead character(1),
    bench_start_time time without time zone,
    if_in_printed smallint,
    usercode bigint,
    updated_on timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar,
    roster_id_misc bigint,
    roster_id_regular bigint
);


ALTER TABLE master.sitting_plan_court_details OWNER TO postgres;

--
-- Name: sitting_plan_court_details_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.sitting_plan_court_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.sitting_plan_court_details_id_seq OWNER TO postgres;

--
-- Name: sitting_plan_court_details_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.sitting_plan_court_details_id_seq OWNED BY master.sitting_plan_court_details.id;


--
-- Name: sitting_plan_details; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.sitting_plan_details (
    id bigint NOT NULL,
    next_dt date,
    if_finalized smallint DEFAULT '0'::smallint,
    no_of_times_modified_before_finalization bigint DEFAULT '0'::bigint,
    no_of_times_modified_after_finalization bigint DEFAULT '0'::bigint,
    if_roster_generated_misc smallint DEFAULT '0'::smallint,
    if_roster_generated_regular smallint DEFAULT '0'::smallint,
    user_ip character varying(45),
    display character(1) DEFAULT 'Y'::bpchar,
    usercode bigint,
    updated_on timestamp with time zone
);


ALTER TABLE master.sitting_plan_details OWNER TO postgres;

--
-- Name: sitting_plan_details_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.sitting_plan_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.sitting_plan_details_id_seq OWNER TO postgres;

--
-- Name: sitting_plan_details_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.sitting_plan_details_id_seq OWNED BY master.sitting_plan_details.id;


--
-- Name: sitting_plan_judges_details; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.sitting_plan_judges_details (
    id bigint NOT NULL,
    sitting_plan_court_details_id bigint,
    jcode bigint,
    updated_on timestamp with time zone,
    usercode bigint,
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE master.sitting_plan_judges_details OWNER TO postgres;

--
-- Name: sitting_plan_judges_details_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.sitting_plan_judges_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.sitting_plan_judges_details_id_seq OWNER TO postgres;

--
-- Name: sitting_plan_judges_details_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.sitting_plan_judges_details_id_seq OWNED BY master.sitting_plan_judges_details.id;


--
-- Name: sitting_plan_judges_leave_details; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.sitting_plan_judges_leave_details (
    id bigint NOT NULL,
    next_dt date,
    jcode bigint,
    is_on_leave smallint DEFAULT '0'::smallint,
    usercode bigint,
    updated_on timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE master.sitting_plan_judges_leave_details OWNER TO postgres;

--
-- Name: sitting_plan_judges_leave_details_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.sitting_plan_judges_leave_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.sitting_plan_judges_leave_details_id_seq OWNER TO postgres;

--
-- Name: sitting_plan_judges_leave_details_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.sitting_plan_judges_leave_details_id_seq OWNED BY master.sitting_plan_judges_leave_details.id;


--
-- Name: specific_role; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.specific_role (
    id bigint NOT NULL,
    usercode bigint NOT NULL,
    flag character(1) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    entered_on timestamp with time zone,
    entered_user bigint DEFAULT 1 NOT NULL
);


ALTER TABLE master.specific_role OWNER TO postgres;

--
-- Name: specific_role_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.specific_role_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.specific_role_id_seq OWNER TO postgres;

--
-- Name: specific_role_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.specific_role_id_seq OWNED BY master.specific_role.id;


--
-- Name: stakeholder_details; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.stakeholder_details (
    id bigint NOT NULL,
    stakeholder_type_id bigint,
    nodal_officer_name character varying(55),
    jcn_email_id character varying(45),
    mobile_number bigint,
    used_from timestamp with time zone,
    used_to timestamp with time zone,
    is_deleted boolean DEFAULT false,
    created_on timestamp with time zone,
    created_by bigint,
    updated_on timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_by bigint,
    nodal_officer_designation character varying(50),
    state_id bigint,
    district_id bigint,
    bench_id bigint,
    jail_state_id bigint,
    jail_district_id bigint,
    address text,
    pincode bigint,
    phone_no character varying(22),
    jail_id character varying(222),
    tribunal_id bigint,
    tribunal_state_id bigint,
    cmis_state_id bigint,
    official_email_id character varying(45)
);


ALTER TABLE master.stakeholder_details OWNER TO postgres;

--
-- Name: stakeholder_details_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.stakeholder_details_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.stakeholder_details_id_seq OWNER TO postgres;

--
-- Name: stakeholder_details_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.stakeholder_details_id_seq OWNED BY master.stakeholder_details.id;


--
-- Name: stampreg; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.stampreg (
    desc1 character varying(10)
);


ALTER TABLE master.stampreg OWNER TO postgres;

--
-- Name: state; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.state (
    state_code bigint NOT NULL,
    district_code bigint NOT NULL,
    sub_dist_code bigint NOT NULL,
    village_code bigint NOT NULL,
    name character varying(255) NOT NULL,
    id_no bigint NOT NULL,
    display character(1) NOT NULL,
    dj_email_id character varying(50),
    sp_email character varying(50),
    cltor_emil character varying(50) NOT NULL,
    region character varying(3) NOT NULL,
    plc_grade bigint NOT NULL,
    sci_state_id bigint NOT NULL,
    ref_code_id bigint NOT NULL,
    pincode character varying(6) NOT NULL,
    ent_user bigint,
    ent_time timestamp with time zone,
    ent_ip_address character varying(45),
    name_hindi character varying(255)
);


ALTER TABLE master.state OWNER TO postgres;

--
-- Name: state_id_no_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.state_id_no_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.state_id_no_seq OWNER TO postgres;

--
-- Name: state_id_no_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.state_id_no_seq OWNED BY master.state.id_no;


--
-- Name: sub_me_per; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.sub_me_per (
    id bigint NOT NULL,
    sub_us_code bigint NOT NULL,
    mn_me_per bigint NOT NULL,
    sub_me_per bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying
);


ALTER TABLE master.sub_me_per OWNER TO postgres;

--
-- Name: sub_me_per_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.sub_me_per_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.sub_me_per_id_seq OWNER TO postgres;

--
-- Name: sub_me_per_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.sub_me_per_id_seq OWNED BY master.sub_me_per.id;


--
-- Name: sub_report; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.sub_report (
    id bigint NOT NULL,
    ref_main_report bigint,
    "desc" character varying(500),
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE master.sub_report OWNER TO postgres;

--
-- Name: sub_report_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.sub_report_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.sub_report_id_seq OWNER TO postgres;

--
-- Name: sub_report_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.sub_report_id_seq OWNED BY master.sub_report.id;


--
-- Name: sub_sub_me_per; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.sub_sub_me_per (
    id bigint NOT NULL,
    sub_sub_us_code bigint,
    sub_me_per_id bigint,
    sub_sub_menu bigint,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL
);


ALTER TABLE master.sub_sub_me_per OWNER TO postgres;

--
-- Name: sub_sub_me_per_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.sub_sub_me_per_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.sub_sub_me_per_id_seq OWNER TO postgres;

--
-- Name: sub_sub_me_per_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.sub_sub_me_per_id_seq OWNED BY master.sub_sub_me_per.id;


--
-- Name: sub_sub_menu; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.sub_sub_menu (
    su_su_menu_id bigint NOT NULL,
    su_menu_id bigint NOT NULL,
    sub_sub_mn_nm character varying(500) NOT NULL,
    url character varying(5000) NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    purpose character varying(5000)
);


ALTER TABLE master.sub_sub_menu OWNER TO postgres;

--
-- Name: sub_sub_menu_su_su_menu_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.sub_sub_menu_su_su_menu_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.sub_sub_menu_su_su_menu_id_seq OWNER TO postgres;

--
-- Name: sub_sub_menu_su_su_menu_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.sub_sub_menu_su_su_menu_id_seq OWNED BY master.sub_sub_menu.su_su_menu_id;


--
-- Name: subheading; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.subheading (
    stagecode bigint DEFAULT '0'::bigint NOT NULL,
    stagename character varying(150),
    listtype character(1) NOT NULL,
    priority bigint,
    display character(1) DEFAULT ''::bpchar NOT NULL,
    board_type public.subheading_board_type NOT NULL,
    national_code bigint,
    shortname character varying(100),
    stagename_hindi character varying(100)
);


ALTER TABLE master.subheading OWNER TO postgres;

--
-- Name: submaster; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.submaster (
    id bigint DEFAULT '0'::bigint NOT NULL,
    subcode1 bigint DEFAULT 0,
    subcode2 character varying(5) DEFAULT '0'::character varying,
    subcode3 bigint DEFAULT '0'::bigint,
    subcode4 bigint DEFAULT '0'::bigint,
    sub_name1 character varying(250) NOT NULL,
    short_description character varying(50),
    sub_name2 character varying(250) NOT NULL,
    sub_name3 character varying(250) NOT NULL,
    sub_name4 character varying(250) NOT NULL,
    subject_description character varying(250),
    category_description character varying(600),
    display character(1),
    flag character(1) NOT NULL,
    list_display character varying(100) NOT NULL,
    updated_on timestamp with time zone,
    id_sc_old bigint NOT NULL,
    subject_sc_old character varying(6) NOT NULL,
    category_sc_old character varying(6) NOT NULL,
    subcode1_hc bigint,
    subcode2_hc bigint,
    subcode3_hc bigint,
    subcode4_hc bigint,
    match_id bigint,
    main_head character(1) NOT NULL,
    flag_use character(1) DEFAULT 'S'::bpchar NOT NULL,
    old_sc_c_kk bigint NOT NULL,
    sub_name1_hindi character varying(250),
    sub_name4_hindi character varying(250),
    is_old character varying(2),
    mapping_id character varying(250)
);


ALTER TABLE master.submaster OWNER TO postgres;

--
-- Name: submenu; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.submenu (
    su_menu_id bigint NOT NULL,
    id bigint NOT NULL,
    sub_mn_nm character varying(500) NOT NULL,
    o_d bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying
);


ALTER TABLE master.submenu OWNER TO postgres;

--
-- Name: submenu_su_menu_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.submenu_su_menu_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.submenu_su_menu_id_seq OWNER TO postgres;

--
-- Name: submenu_su_menu_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.submenu_su_menu_id_seq OWNED BY master.submenu.su_menu_id;


--
-- Name: t_category_master; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.t_category_master (
    id bigint NOT NULL,
    name character varying(75) NOT NULL,
    parent_id smallint,
    destination_id numeric(2,0),
    cis_causelist_type_id character varying(20),
    access_id character varying(17) NOT NULL,
    access_dated timestamp with time zone,
    record_status numeric(3,0) DEFAULT '0'::numeric NOT NULL
);


ALTER TABLE master.t_category_master OWNER TO postgres;

--
-- Name: COLUMN t_category_master.destination_id; Type: COMMENT; Schema: master; Owner: postgres
--

COMMENT ON COLUMN master.t_category_master.destination_id IS 'It will have a value only if parent_id is null for that corresponding row.';


--
-- Name: t_category_master_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.t_category_master_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.t_category_master_id_seq OWNER TO postgres;

--
-- Name: t_category_master_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.t_category_master_id_seq OWNED BY master.t_category_master.id;


--
-- Name: t_doc_details; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.t_doc_details (
    id character varying(17) NOT NULL,
    title character varying(160),
    category_id smallint NOT NULL,
    destination_id numeric(2,0) NOT NULL,
    document_id character varying(17),
    destination_directory character varying(1000),
    dated date,
    from_date date,
    to_date date,
    by_user_id character varying(9) NOT NULL,
    authorized_by character varying(60),
    access_id character varying(17) NOT NULL,
    access_dated timestamp with time zone,
    record_status numeric(3,0) DEFAULT '0'::numeric NOT NULL
);


ALTER TABLE master.t_doc_details OWNER TO postgres;

--
-- Name: tbl_user; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.tbl_user (
    id bigint NOT NULL,
    user_name character varying(255),
    user_email character varying(255),
    user_password character varying(255),
    user_department character varying(255),
    user_type character varying(255),
    user_designation character varying(45),
    last_pwd_reset timestamp with time zone,
    status public.tbl_user_status DEFAULT '1'::public.tbl_user_status,
    created_by character varying(255),
    created_on timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_on timestamp with time zone,
    updated_by character varying(100)
);


ALTER TABLE master.tbl_user OWNER TO postgres;

--
-- Name: tbl_usercode_changed; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.tbl_usercode_changed (
    diary_no bigint NOT NULL,
    usercode bigint
);


ALTER TABLE master.tbl_usercode_changed OWNER TO postgres;

--
-- Name: token_status; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.token_status (
    id bigint NOT NULL,
    pending character(1),
    resolved character(1),
    technical_issue character(1),
    in_process character(1),
    resolved_by_tt character(1),
    pending_from timestamp with time zone,
    pending_to timestamp with time zone,
    technical_issue_from timestamp with time zone,
    technical_issue_to timestamp with time zone,
    resolved_date_by_tt timestamp with time zone,
    resolved_by_dmt timestamp with time zone,
    resolved_dmt_user bigint,
    technical_issue_dmt_user bigint,
    technical_issue_assign_to bigint,
    resolved_by_tt_user bigint,
    technical_issue_assign_date timestamp with time zone
);


ALTER TABLE master.token_status OWNER TO postgres;

--
-- Name: tribunal; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.tribunal (
    id bigint DEFAULT '0'::bigint NOT NULL,
    agency_name character varying(100) NOT NULL,
    state_id bigint NOT NULL,
    short_agency_name character varying(8) NOT NULL
);


ALTER TABLE master.tribunal OWNER TO postgres;

--
-- Name: tw_max_process; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.tw_max_process (
    id bigint NOT NULL,
    year bigint NOT NULL,
    processid bigint NOT NULL,
    tw_max_ack bigint NOT NULL,
    tw_disp_id bigint NOT NULL,
    tw_disp_reg bigint NOT NULL,
    tw_disp_adv_reg bigint NOT NULL,
    office_report bigint NOT NULL
);


ALTER TABLE master.tw_max_process OWNER TO postgres;

--
-- Name: tw_max_process_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.tw_max_process_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.tw_max_process_id_seq OWNER TO postgres;

--
-- Name: tw_max_process_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.tw_max_process_id_seq OWNED BY master.tw_max_process.id;


--
-- Name: tw_notice; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.tw_notice (
    id bigint NOT NULL,
    name character varying(300) NOT NULL,
    nature character varying(1) NOT NULL,
    section bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    fly_rep bigint NOT NULL,
    sig_authority bigint NOT NULL,
    war_notice character varying(100),
    notice_office character varying(1),
    notice_status character varying(1) DEFAULT 'P'::character varying,
    doc_ia_type character varying(20) NOT NULL
);


ALTER TABLE master.tw_notice OWNER TO postgres;

--
-- Name: tw_notice_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.tw_notice_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.tw_notice_id_seq OWNER TO postgres;

--
-- Name: tw_notice_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.tw_notice_id_seq OWNED BY master.tw_notice.id;


--
-- Name: tw_pf_his; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.tw_pf_his (
    id bigint NOT NULL,
    fil_no character varying(14) NOT NULL,
    order_dt date,
    tw_status bigint NOT NULL
);


ALTER TABLE master.tw_pf_his OWNER TO postgres;

--
-- Name: tw_pf_his_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.tw_pf_his_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.tw_pf_his_id_seq OWNER TO postgres;

--
-- Name: tw_pf_his_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.tw_pf_his_id_seq OWNED BY master.tw_pf_his.id;


--
-- Name: tw_pin_code; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.tw_pin_code (
    id bigint NOT NULL,
    state_code bigint NOT NULL,
    district_code bigint NOT NULL,
    pin_code bigint NOT NULL
);


ALTER TABLE master.tw_pin_code OWNER TO postgres;

--
-- Name: tw_pin_code_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.tw_pin_code_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.tw_pin_code_id_seq OWNER TO postgres;

--
-- Name: tw_pin_code_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.tw_pin_code_id_seq OWNED BY master.tw_pin_code.id;


--
-- Name: tw_section; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.tw_section (
    id bigint NOT NULL,
    name character varying(300) NOT NULL
);


ALTER TABLE master.tw_section OWNER TO postgres;

--
-- Name: tw_section_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.tw_section_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.tw_section_id_seq OWNER TO postgres;

--
-- Name: tw_section_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.tw_section_id_seq OWNED BY master.tw_section.id;


--
-- Name: tw_send_to; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.tw_send_to (
    id bigint NOT NULL,
    desg character varying(300) NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL
);


ALTER TABLE master.tw_send_to OWNER TO postgres;

--
-- Name: tw_send_to_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.tw_send_to_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.tw_send_to_id_seq OWNER TO postgres;

--
-- Name: tw_send_to_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.tw_send_to_id_seq OWNED BY master.tw_send_to.id;


--
-- Name: tw_serve; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.tw_serve (
    id bigint NOT NULL,
    serve_stage bigint NOT NULL,
    serve_type bigint NOT NULL,
    name character varying(50) NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL
);


ALTER TABLE master.tw_serve OWNER TO postgres;

--
-- Name: tw_serve_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.tw_serve_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.tw_serve_id_seq OWNER TO postgres;

--
-- Name: tw_serve_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.tw_serve_id_seq OWNED BY master.tw_serve.id;


--
-- Name: tw_weight_or; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.tw_weight_or (
    id bigint NOT NULL,
    from_weight bigint NOT NULL,
    to_weight bigint NOT NULL,
    ord_price double precision NOT NULL,
    fr_dt date,
    to_dt date,
    del_type character varying(1) NOT NULL
);


ALTER TABLE master.tw_weight_or OWNER TO postgres;

--
-- Name: tw_weight_or_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.tw_weight_or_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.tw_weight_or_id_seq OWNER TO postgres;

--
-- Name: tw_weight_or_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.tw_weight_or_id_seq OWNED BY master.tw_weight_or.id;


--
-- Name: user_d_t_map; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.user_d_t_map (
    id bigint NOT NULL,
    udept bigint NOT NULL,
    utype bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    entuser bigint NOT NULL,
    entdt timestamp with time zone,
    upuser bigint NOT NULL,
    updt timestamp with time zone
);


ALTER TABLE master.user_d_t_map OWNER TO postgres;

--
-- Name: user_d_t_map_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.user_d_t_map_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.user_d_t_map_id_seq OWNER TO postgres;

--
-- Name: user_d_t_map_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.user_d_t_map_id_seq OWNED BY master.user_d_t_map.id;


--
-- Name: user_l_map; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.user_l_map (
    id bigint NOT NULL,
    udept bigint NOT NULL,
    utype bigint NOT NULL,
    ucode bigint NOT NULL,
    l_type bigint NOT NULL,
    f_auth bigint NOT NULL,
    a_auth bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    "user" bigint NOT NULL,
    ent_dt timestamp with time zone,
    up_user bigint NOT NULL,
    up_entdt timestamp with time zone
);


ALTER TABLE master.user_l_map OWNER TO postgres;

--
-- Name: user_l_map_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.user_l_map_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.user_l_map_id_seq OWNER TO postgres;

--
-- Name: user_l_map_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.user_l_map_id_seq OWNED BY master.user_l_map.id;


--
-- Name: user_l_type; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.user_l_type (
    id bigint NOT NULL,
    name character varying(50),
    s_name character varying(10),
    display character varying(1) DEFAULT 'Y'::character varying
);


ALTER TABLE master.user_l_type OWNER TO postgres;

--
-- Name: user_l_type_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.user_l_type_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.user_l_type_id_seq OWNER TO postgres;

--
-- Name: user_l_type_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.user_l_type_id_seq OWNED BY master.user_l_type.id;


--
-- Name: user_range; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.user_range (
    id bigint NOT NULL,
    utype bigint NOT NULL,
    low bigint NOT NULL,
    up bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    entuser bigint NOT NULL,
    entdt timestamp with time zone,
    upuser bigint NOT NULL,
    updt timestamp with time zone
);


ALTER TABLE master.user_range OWNER TO postgres;

--
-- Name: user_range_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.user_range_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.user_range_id_seq OWNER TO postgres;

--
-- Name: user_range_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.user_range_id_seq OWNED BY master.user_range.id;


--
-- Name: user_role_master_mapping; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.user_role_master_mapping (
    id bigint NOT NULL,
    usercode bigint,
    role_master_id bigint,
    display character(1) DEFAULT 'Y'::bpchar,
    updated_by bigint,
    updated_on timestamp with time zone
);


ALTER TABLE master.user_role_master_mapping OWNER TO postgres;

--
-- Name: user_role_master_mapping_history; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.user_role_master_mapping_history (
    id bigint NOT NULL,
    usercode bigint,
    role_master_id bigint,
    display character(1) DEFAULT 'Y'::bpchar,
    updated_by bigint,
    updated_on timestamp with time zone
);


ALTER TABLE master.user_role_master_mapping_history OWNER TO postgres;

--
-- Name: user_role_master_mapping_history_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.user_role_master_mapping_history_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.user_role_master_mapping_history_id_seq OWNER TO postgres;

--
-- Name: user_role_master_mapping_history_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.user_role_master_mapping_history_id_seq OWNED BY master.user_role_master_mapping_history.id;


--
-- Name: user_role_master_mapping_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.user_role_master_mapping_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.user_role_master_mapping_id_seq OWNER TO postgres;

--
-- Name: user_role_master_mapping_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.user_role_master_mapping_id_seq OWNED BY master.user_role_master_mapping.id;


--
-- Name: user_sec_map; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.user_sec_map (
    id bigint NOT NULL,
    empid bigint,
    usec bigint,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    updated_on timestamp with time zone,
    updated_by bigint DEFAULT '0'::bigint NOT NULL
);


ALTER TABLE master.user_sec_map OWNER TO postgres;

--
-- Name: user_sec_map_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.user_sec_map_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.user_sec_map_id_seq OWNER TO postgres;

--
-- Name: user_sec_map_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.user_sec_map_id_seq OWNED BY master.user_sec_map.id;


--
-- Name: userdept; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.userdept (
    id bigint NOT NULL,
    dept_name character varying(20) NOT NULL,
    uside_flag character varying(10) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    entuser bigint NOT NULL,
    entdt timestamp with time zone,
    upuser bigint NOT NULL,
    updt timestamp with time zone
);


ALTER TABLE master.userdept OWNER TO postgres;

--
-- Name: userdept_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.userdept_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.userdept_id_seq OWNER TO postgres;

--
-- Name: userdept_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.userdept_id_seq OWNED BY master.userdept.id;


--
-- Name: users; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.users (
    usercode bigint NOT NULL,
    userpass character varying(100),
    name character varying(30) NOT NULL,
    empid bigint,
    service character varying(1) NOT NULL,
    usertype bigint DEFAULT '2'::bigint,
    section bigint NOT NULL,
    udept bigint,
    log_in timestamp with time zone,
    logout timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar,
    jcode bigint DEFAULT 0 NOT NULL,
    nm_alias character varying(500) NOT NULL,
    entdt timestamp with time zone,
    entuser bigint NOT NULL,
    attend public.users_attend DEFAULT 'P'::public.users_attend,
    upuser bigint NOT NULL,
    updt timestamp with time zone,
    mobile_no character varying(45),
    email_id character varying(100),
    ip_address character varying(200),
    is_courtmaster character varying(2) DEFAULT 'N'::character varying NOT NULL,
    dob date,
    mobile character varying(45),
    uphoto character varying(200),
    create_modify timestamp with time zone,
    updated_on timestamp with time zone,
    updated_by bigint,
    updated_by_ip character varying(100)
);


ALTER TABLE master.users OWNER TO postgres;

--
-- Name: users_usercode_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.users_usercode_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.users_usercode_seq OWNER TO postgres;

--
-- Name: users_usercode_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.users_usercode_seq OWNED BY master.users.usercode;


--
-- Name: usersection; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.usersection (
    id bigint NOT NULL,
    section_name character varying(20),
    description text,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    old_id bigint NOT NULL,
    entuser bigint NOT NULL,
    entdt timestamp with time zone,
    upuser bigint NOT NULL,
    updt timestamp with time zone,
    isda public.usersection_isda DEFAULT 'N'::public.usersection_isda NOT NULL,
    email_id character varying(45),
    create_modify timestamp with time zone,
    updated_on timestamp with time zone,
    updated_by bigint,
    updated_by_ip character varying(100)
);


ALTER TABLE master.usersection OWNER TO postgres;

--
-- Name: usersection_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.usersection_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.usersection_id_seq OWNER TO postgres;

--
-- Name: usersection_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.usersection_id_seq OWNED BY master.usersection.id;


--
-- Name: usertype; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.usertype (
    id bigint NOT NULL,
    type_name character varying(50),
    disp_flag character varying(10) NOT NULL,
    mgmt_flag character varying(10) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar,
    entuser bigint NOT NULL,
    entdt timestamp with time zone,
    upuser bigint NOT NULL,
    updt timestamp with time zone
);


ALTER TABLE master.usertype OWNER TO postgres;

--
-- Name: usertype_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.usertype_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.usertype_id_seq OWNER TO postgres;

--
-- Name: usertype_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.usertype_id_seq OWNED BY master.usertype.id;


--
-- Name: vernacular_languages; Type: TABLE; Schema: master; Owner: postgres
--

CREATE TABLE master.vernacular_languages (
    id bigint NOT NULL,
    name character varying(45) NOT NULL,
    display_name text NOT NULL,
    short_name character varying(45),
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE master.vernacular_languages OWNER TO postgres;

--
-- Name: vernacular_languages_id_seq; Type: SEQUENCE; Schema: master; Owner: postgres
--

CREATE SEQUENCE master.vernacular_languages_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE master.vernacular_languages_id_seq OWNER TO postgres;

--
-- Name: vernacular_languages_id_seq; Type: SEQUENCE OWNED BY; Schema: master; Owner: postgres
--

ALTER SEQUENCE master.vernacular_languages_id_seq OWNED BY master.vernacular_languages.id;


--
-- Name: a_series; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.a_series (
    no bigint NOT NULL
);


ALTER TABLE public.a_series OWNER TO postgres;

--
-- Name: abr_accused; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.abr_accused (
    diary_no bigint NOT NULL,
    ord_dt date NOT NULL,
    p_r character varying(1) NOT NULL,
    p_r_side bigint NOT NULL,
    acc_ent_time timestamp with time zone,
    allot_to bigint NOT NULL
);


ALTER TABLE public.abr_accused OWNER TO postgres;

--
-- Name: ac; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ac (
    id bigint NOT NULL,
    aor_code bigint NOT NULL,
    cname character varying(500) NOT NULL,
    cfname character varying(200) NOT NULL,
    pa_line1 character varying(100) NOT NULL,
    pa_line2 character varying(100) NOT NULL,
    pa_district character varying(100) NOT NULL,
    pa_pin bigint NOT NULL,
    ppa_line1 character varying(100) NOT NULL,
    ppa_line2 character varying(100) NOT NULL,
    ppa_district character varying(100) NOT NULL,
    ppa_pin bigint NOT NULL,
    dob date,
    place_birth character varying(100) NOT NULL,
    nationality character varying(25) NOT NULL,
    cmobile bigint DEFAULT '0'::bigint NOT NULL,
    eq_x character varying(1) NOT NULL,
    eq_xii character varying(3) NOT NULL,
    eq_ug character varying(10) NOT NULL,
    eq_pg character varying(10) NOT NULL,
    eino bigint NOT NULL,
    regdate date,
    status bigint DEFAULT 1 NOT NULL,
    updatedby bigint NOT NULL,
    updatedon timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updatedip character varying(45) NOT NULL,
    " modified_on" character varying(45),
    modified_by character varying(45),
    " modified_ip" character varying(45)
);


ALTER TABLE public.ac OWNER TO postgres;

--
-- Name: ac_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ac_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ac_id_seq OWNER TO postgres;

--
-- Name: ac_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ac_id_seq OWNED BY public.ac.id;


--
-- Name: act_main; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.act_main (
    id bigint NOT NULL,
    diary_no bigint,
    act bigint NOT NULL,
    entdt timestamp with time zone,
    "user" bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar,
    updated_from_ip character varying(15),
    updatedfrommodule character varying(45),
    create_modify timestamp with time zone
);


ALTER TABLE public.act_main OWNER TO postgres;

--
-- Name: act_main_caveat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.act_main_caveat (
    id bigint NOT NULL,
    caveat_no bigint,
    act bigint NOT NULL,
    entdt timestamp with time zone,
    "user" bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar,
    updated_from_ip character varying(15) DEFAULT NULL::character varying,
    updatedfrommodule character varying(45) DEFAULT NULL::character varying,
    create_modify timestamp with time zone
);


ALTER TABLE public.act_main_caveat OWNER TO postgres;

--
-- Name: act_main_caveat_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.act_main_caveat_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.act_main_caveat_id_seq OWNER TO postgres;

--
-- Name: act_main_caveat_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.act_main_caveat_id_seq OWNED BY public.act_main_caveat.id;


--
-- Name: act_main_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.act_main_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.act_main_id_seq OWNER TO postgres;

--
-- Name: act_main_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.act_main_id_seq OWNED BY public.act_main.id;


--
-- Name: admin; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.admin (
    id bigint NOT NULL,
    fullname character varying(100),
    adminemail character varying(120),
    username character varying(100) NOT NULL,
    password character varying(100) NOT NULL,
    updationdate timestamp with time zone,
    user_type character varying(254),
    role_id bigint,
    phone_number character varying(45),
    alternative_phone_no character varying(45),
    created_on timestamp with time zone,
    status bigint DEFAULT '1'::bigint,
    icmis_user_id bigint,
    court_no bigint DEFAULT '0'::bigint
);


ALTER TABLE public.admin OWNER TO postgres;

--
-- Name: admin_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.admin_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.admin_id_seq OWNER TO postgres;

--
-- Name: admin_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.admin_id_seq OWNED BY public.admin.id;


--
-- Name: admin_user_permission; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.admin_user_permission (
    id bigint NOT NULL,
    permission_desc character varying(245),
    level bigint,
    perm_id bigint,
    status bigint DEFAULT '1'::bigint
);


ALTER TABLE public.admin_user_permission OWNER TO postgres;

--
-- Name: admin_user_permission_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.admin_user_permission_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.admin_user_permission_id_seq OWNER TO postgres;

--
-- Name: admin_user_permission_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.admin_user_permission_id_seq OWNED BY public.admin_user_permission.id;


--
-- Name: admin_user_roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.admin_user_roles (
    role_id bigint NOT NULL,
    role_name character varying(255) NOT NULL
);


ALTER TABLE public.admin_user_roles OWNER TO postgres;

--
-- Name: admin_usr_roles_permission; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.admin_usr_roles_permission (
    id bigint NOT NULL,
    role_id character varying(45),
    permission_id character varying(45),
    status bigint DEFAULT '0'::bigint,
    created_on character varying(45) DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.admin_usr_roles_permission OWNER TO postgres;

--
-- Name: advance_allocated; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.advance_allocated (
    id bigint NOT NULL,
    diary_no character varying(50),
    conn_key bigint,
    next_dt date,
    subhead bigint,
    board_type character(1),
    clno bigint,
    brd_slno bigint,
    j1 bigint,
    j2 bigint,
    j3 bigint,
    listorder bigint,
    usercode bigint,
    ent_dt timestamp with time zone,
    main_supp_flag bigint
);


ALTER TABLE public.advance_allocated OWNER TO postgres;

--
-- Name: advance_allocated_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.advance_allocated_a (
    id bigint,
    diary_no bigint,
    conn_key bigint,
    next_dt date,
    subhead bigint,
    board_type character(1),
    clno bigint,
    brd_slno bigint,
    j1 bigint,
    j2 bigint,
    j3 bigint,
    listorder bigint,
    usercode bigint,
    ent_dt timestamp with time zone,
    main_supp_flag bigint
);


ALTER TABLE public.advance_allocated_a OWNER TO dev;

--
-- Name: advance_allocated_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.advance_allocated_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.advance_allocated_id_seq OWNER TO postgres;

--
-- Name: advance_allocated_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.advance_allocated_id_seq OWNED BY public.advance_allocated.id;


--
-- Name: advance_cl_printed; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.advance_cl_printed (
    id bigint NOT NULL,
    next_dt date,
    board_type character(1) NOT NULL,
    part bigint NOT NULL,
    main_supp bigint DEFAULT 0,
    from_brd_no bigint DEFAULT 0 NOT NULL,
    to_brd_no bigint DEFAULT 0 NOT NULL,
    j1 bigint DEFAULT '0'::bigint,
    j2 bigint,
    j3 bigint,
    usercode bigint DEFAULT '0'::bigint NOT NULL,
    ent_time timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE public.advance_cl_printed OWNER TO postgres;

--
-- Name: advance_cl_printed_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.advance_cl_printed_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.advance_cl_printed_id_seq OWNER TO postgres;

--
-- Name: advance_cl_printed_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.advance_cl_printed_id_seq OWNED BY public.advance_cl_printed.id;


--
-- Name: advance_elimination_cl_printed; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.advance_elimination_cl_printed (
    id bigint NOT NULL,
    next_dt date,
    board_type character(1) NOT NULL,
    usercode bigint DEFAULT '0'::bigint NOT NULL,
    ent_time timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE public.advance_elimination_cl_printed OWNER TO postgres;

--
-- Name: advance_elimination_cl_printed_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.advance_elimination_cl_printed_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.advance_elimination_cl_printed_id_seq OWNER TO postgres;

--
-- Name: advance_elimination_cl_printed_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.advance_elimination_cl_printed_id_seq OWNED BY public.advance_elimination_cl_printed.id;


--
-- Name: advance_single_judge_allocated; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.advance_single_judge_allocated (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    next_dt date,
    from_dt date,
    to_dt date,
    subhead bigint NOT NULL,
    board_type character(1) NOT NULL,
    clno bigint DEFAULT '1'::bigint NOT NULL,
    brd_slno bigint NOT NULL,
    listorder bigint NOT NULL,
    main_supp_flag bigint DEFAULT '1'::bigint NOT NULL,
    weekly_no bigint NOT NULL,
    weekly_year bigint NOT NULL,
    usercode bigint NOT NULL,
    ent_dt timestamp with time zone
);


ALTER TABLE public.advance_single_judge_allocated OWNER TO postgres;

--
-- Name: advance_single_judge_allocated_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.advance_single_judge_allocated_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.advance_single_judge_allocated_id_seq OWNER TO postgres;

--
-- Name: advance_single_judge_allocated_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.advance_single_judge_allocated_id_seq OWNED BY public.advance_single_judge_allocated.id;


--
-- Name: advance_single_judge_allocated_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.advance_single_judge_allocated_log (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    next_dt date,
    from_dt date,
    to_dt date,
    subhead bigint NOT NULL,
    board_type character(1) NOT NULL,
    clno bigint DEFAULT '1'::bigint NOT NULL,
    brd_slno bigint NOT NULL,
    listorder bigint NOT NULL,
    main_supp_flag bigint DEFAULT '1'::bigint NOT NULL,
    weekly_no bigint NOT NULL,
    weekly_year bigint NOT NULL,
    usercode bigint NOT NULL,
    ent_dt timestamp with time zone,
    log_sent_on timestamp with time zone,
    log_sent_by bigint NOT NULL
);


ALTER TABLE public.advance_single_judge_allocated_log OWNER TO postgres;

--
-- Name: advanced_drop_note; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.advanced_drop_note (
    id bigint NOT NULL,
    cl_date date,
    clno bigint NOT NULL,
    diary_no character varying(50) NOT NULL,
    roster_id bigint NOT NULL,
    nrs character varying(75),
    usercode bigint NOT NULL,
    ent_dt timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    mf character(1),
    update_time timestamp with time zone,
    update_user character varying(5) NOT NULL,
    so_user character varying(5) NOT NULL,
    so_time timestamp with time zone,
    part bigint NOT NULL,
    board_type character(1) DEFAULT 'J'::bpchar
);


ALTER TABLE public.advanced_drop_note OWNER TO postgres;

--
-- Name: advanced_drop_note_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.advanced_drop_note_a (
    id bigint,
    cl_date date,
    clno bigint,
    diary_no bigint,
    roster_id bigint,
    nrs character varying(75),
    usercode bigint,
    ent_dt timestamp with time zone,
    display character(1),
    mf character(1),
    update_time timestamp with time zone,
    update_user character varying(5),
    so_user character varying(5),
    so_time timestamp with time zone,
    part bigint,
    board_type character(1)
);


ALTER TABLE public.advanced_drop_note_a OWNER TO dev;

--
-- Name: advanced_drop_note_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.advanced_drop_note_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.advanced_drop_note_id_seq OWNER TO postgres;

--
-- Name: advanced_drop_note_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.advanced_drop_note_id_seq OWNED BY public.advanced_drop_note.id;


--
-- Name: advocate; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.advocate (
    diary_no bigint,
    adv_type character(1),
    pet_res character(1),
    pet_res_no bigint DEFAULT 0 NOT NULL,
    advocate_id bigint,
    adv character varying(60),
    usercode bigint,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar,
    stateadv character(1) DEFAULT 'N'::bpchar NOT NULL,
    old_adv character varying(100),
    ent_by_caveat_advocate bigint,
    remark bigint,
    aor_state character(1) DEFAULT 'A'::bpchar,
    pet_res_show_no character varying(100),
    is_ac character(1),
    writ_adv_remarks character varying(200),
    ac_direction_given_by character varying(200),
    ac_remarks character varying(200),
    inperson_mobile character varying(45),
    inperson_email character varying(100),
    create_modify timestamp without time zone,
    updated_by bigint,
    updated_by_ip text
);


ALTER TABLE public.advocate OWNER TO postgres;

--
-- Name: advocate_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.advocate_a (
    diary_no bigint,
    adv_type character(1),
    pet_res character(1),
    pet_res_no bigint,
    advocate_id bigint,
    adv character varying(60),
    usercode bigint,
    ent_dt timestamp with time zone,
    display character(1),
    stateadv character(1),
    old_adv character varying(100),
    ent_by_caveat_advocate bigint,
    remark bigint,
    aor_state character(1),
    pet_res_show_no character varying(100),
    is_ac character(1),
    writ_adv_remarks character varying(200),
    ac_direction_given_by character varying(200),
    ac_remarks character varying(200),
    inperson_mobile character varying(45),
    inperson_email character varying(100)
);


ALTER TABLE public.advocate_a OWNER TO dev;

--
-- Name: advocate_requisition_request; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.advocate_requisition_request (
    id bigint NOT NULL,
    req_id bigint,
    file_type character varying(100),
    file_text character varying(255),
    file_name character varying(255),
    created_on timestamp with time zone,
    created_by character varying(255),
    updated_on date
);


ALTER TABLE public.advocate_requisition_request OWNER TO postgres;

--
-- Name: advocate_requisition_request_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.advocate_requisition_request_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.advocate_requisition_request_id_seq OWNER TO postgres;

--
-- Name: advocate_requisition_request_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.advocate_requisition_request_id_seq OWNED BY public.advocate_requisition_request.id;


--
-- Name: all_reg_no; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.all_reg_no (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    active_fil_no character varying(16) NOT NULL,
    ct bigint DEFAULT 0 NOT NULL,
    from_no bigint DEFAULT 0 NOT NULL,
    to_no bigint DEFAULT 0 NOT NULL,
    active_reg_year bigint NOT NULL,
    no bigint NOT NULL,
    active_fil_dt timestamp with time zone
);


ALTER TABLE public.all_reg_no OWNER TO postgres;

--
-- Name: all_reg_no_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.all_reg_no_a (
    diary_no bigint,
    active_fil_no character varying(16),
    ct bigint,
    from_no bigint,
    to_no bigint,
    active_reg_year bigint,
    no bigint,
    active_fil_dt timestamp with time zone
);


ALTER TABLE public.all_reg_no_a OWNER TO dev;

--
-- Name: allocation_trap; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.allocation_trap (
    id bigint NOT NULL,
    list_dt date,
    is_roster_selected character(1) NOT NULL,
    roster_id bigint NOT NULL,
    fresh_limit bigint NOT NULL,
    old_limit bigint NOT NULL,
    clno character varying(45) NOT NULL,
    main_supp_flag character(1) NOT NULL,
    short_cat_flag character(1) NOT NULL,
    advance_flag character(1) NOT NULL,
    usercode bigint NOT NULL,
    ent_dt timestamp with time zone,
    listorder character varying(100)
);


ALTER TABLE public.allocation_trap OWNER TO postgres;

--
-- Name: allocation_trap_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.allocation_trap_a (
    id bigint,
    list_dt date,
    is_roster_selected character(1),
    roster_id bigint,
    fresh_limit bigint,
    old_limit bigint,
    clno character varying(45),
    main_supp_flag character(1),
    short_cat_flag character(1),
    advance_flag character(1),
    usercode bigint,
    ent_dt timestamp with time zone,
    listorder character varying(100)
);


ALTER TABLE public.allocation_trap_a OWNER TO dev;

--
-- Name: allocation_trap_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.allocation_trap_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.allocation_trap_id_seq OWNER TO postgres;

--
-- Name: allocation_trap_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.allocation_trap_id_seq OWNED BY public.allocation_trap.id;


--
-- Name: amicus_curiae; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.amicus_curiae (
    id bigint NOT NULL,
    bar_id bigint,
    from_date date,
    to_date date,
    is_deleted character varying(2),
    updated_on timestamp with time zone,
    updated_by bigint,
    updated_by_ip character varying(45),
    deleted_on timestamp with time zone,
    deleted_by bigint,
    deleted_by_ip character varying(45),
    last_assigned_on timestamp with time zone
);


ALTER TABLE public.amicus_curiae OWNER TO postgres;

--
-- Name: amicus_curiae_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.amicus_curiae_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.amicus_curiae_id_seq OWNER TO postgres;

--
-- Name: amicus_curiae_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.amicus_curiae_id_seq OWNED BY public.amicus_curiae.id;


--
-- Name: aor_clerk_trainee; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.aor_clerk_trainee (
    id bigint NOT NULL,
    clerk_id_no bigint NOT NULL,
    name character varying(20) NOT NULL,
    pho_no bigint NOT NULL,
    email_id character varying(100) NOT NULL,
    aor_code bigint NOT NULL,
    aor_name character varying(200) NOT NULL,
    willful_participation character(1) NOT NULL,
    updated_on timestamp with time zone,
    ip_address character varying(45) NOT NULL,
    updated_by bigint NOT NULL
);


ALTER TABLE public.aor_clerk_trainee OWNER TO postgres;

--
-- Name: aor_clerk_trainee_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.aor_clerk_trainee_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.aor_clerk_trainee_id_seq OWNER TO postgres;

--
-- Name: aor_clerk_trainee_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.aor_clerk_trainee_id_seq OWNED BY public.aor_clerk_trainee.id;


--
-- Name: auto_coram_allottment; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.auto_coram_allottment (
    diary_no bigint NOT NULL,
    new_coram character varying(45) NOT NULL,
    ent_dt timestamp with time zone
);


ALTER TABLE public.auto_coram_allottment OWNER TO postgres;

--
-- Name: avi; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.avi (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    fil_dt timestamp with time zone,
    c_status character(1),
    a character varying(12),
    b text,
    ss text,
    ss2 character varying(341)
);


ALTER TABLE public.avi OWNER TO postgres;

--
-- Name: brdrem; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.brdrem (
    diary_no character varying(50) NOT NULL,
    remark text,
    usercode bigint,
    ent_dt timestamp with time zone
);


ALTER TABLE public.brdrem OWNER TO postgres;

--
-- Name: brdrem_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.brdrem_a (
    diary_no bigint,
    remark text,
    usercode bigint,
    ent_dt timestamp with time zone
);


ALTER TABLE public.brdrem_a OWNER TO dev;

--
-- Name: brdrem_his; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.brdrem_his (
    diary_no bigint,
    remark text,
    usercode bigint,
    ent_dt timestamp with time zone,
    bh_usercode bigint NOT NULL,
    bh_entdt timestamp with time zone
);


ALTER TABLE public.brdrem_his OWNER TO postgres;

--
-- Name: brdrem_his_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.brdrem_his_a (
    diary_no bigint,
    remark text,
    usercode bigint,
    ent_dt timestamp with time zone,
    bh_usercode bigint,
    bh_entdt timestamp with time zone
);


ALTER TABLE public.brdrem_his_a OWNER TO dev;

--
-- Name: bulk_dismissal_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.bulk_dismissal_log (
    id bigint NOT NULL,
    diary_nos text,
    ucode bigint,
    jcodes character varying(80),
    dismissal_type bigint,
    dismissal_order_dt date,
    entered_on timestamp with time zone,
    rj_date character varying(45)
);


ALTER TABLE public.bulk_dismissal_log OWNER TO postgres;

--
-- Name: bulk_dismissal_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.bulk_dismissal_log_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.bulk_dismissal_log_id_seq OWNER TO postgres;

--
-- Name: bulk_dismissal_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.bulk_dismissal_log_id_seq OWNED BY public.bulk_dismissal_log.id;


--
-- Name: call_listing1_days; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.call_listing1_days (
    id bigint NOT NULL,
    weekday bigint,
    listonday bigint,
    type public.call_listing1_days_type,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE public.call_listing1_days OWNER TO postgres;

--
-- Name: call_listing1_days_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.call_listing1_days_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.call_listing1_days_id_seq OWNER TO postgres;

--
-- Name: call_listing1_days_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.call_listing1_days_id_seq OWNED BY public.call_listing1_days.id;


--
-- Name: case_defect; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.case_defect (
    diary_no bigint,
    save_dt timestamp with time zone,
    usercode bigint,
    org_id bigint,
    rm_dt timestamp with time zone,
    display character(1),
    remarks text
);


ALTER TABLE public.case_defect OWNER TO postgres;

--
-- Name: case_distribution_trap; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.case_distribution_trap (
    id bigint NOT NULL,
    diary_no_list text,
    from_da bigint,
    to_da bigint,
    transaction_date timestamp with time zone,
    remarks character varying(5000)
);


ALTER TABLE public.case_distribution_trap OWNER TO postgres;

--
-- Name: case_distribution_trap_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.case_distribution_trap_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.case_distribution_trap_id_seq OWNER TO postgres;

--
-- Name: case_distribution_trap_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.case_distribution_trap_id_seq OWNED BY public.case_distribution_trap.id;


--
-- Name: case_info; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.case_info (
    id bigint NOT NULL,
    diary_no bigint,
    message character varying(50000),
    insert_time timestamp with time zone,
    usercode bigint,
    userip character varying(100),
    display character varying(45),
    deleted_on timestamp with time zone,
    deleted_by bigint,
    deleted_user_ip character varying(45)
);


ALTER TABLE public.case_info OWNER TO postgres;

--
-- Name: case_info_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.case_info_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.case_info_id_seq OWNER TO postgres;

--
-- Name: case_info_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.case_info_id_seq OWNED BY public.case_info.id;


--
-- Name: case_limit; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.case_limit (
    diary_no bigint,
    limit_days bigint,
    descr character varying(400),
    case_nature bigint NOT NULL,
    under_section bigint NOT NULL,
    o_s character varying(100) NOT NULL,
    pol bigint NOT NULL,
    o_d date,
    f_d date,
    c_d_a date,
    d_o_d date,
    case_lim_display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    id bigint NOT NULL,
    lowerct_id bigint NOT NULL,
    order_cof character varying(1) NOT NULL,
    d_o_a date,
    case_lmt_user bigint NOT NULL,
    case_lmt_ent_dt timestamp with time zone
);


ALTER TABLE public.case_limit OWNER TO postgres;

--
-- Name: case_limit_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.case_limit_a (
    diary_no bigint,
    limit_days bigint,
    descr character varying(400),
    case_nature bigint,
    under_section bigint,
    o_s character varying(100),
    pol bigint,
    o_d date,
    f_d date,
    c_d_a date,
    d_o_d date,
    case_lim_display character varying(1),
    id bigint,
    lowerct_id bigint,
    order_cof character varying(1),
    d_o_a date,
    case_lmt_user bigint,
    case_lmt_ent_dt timestamp with time zone
);


ALTER TABLE public.case_limit_a OWNER TO dev;

--
-- Name: case_limit_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.case_limit_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.case_limit_id_seq OWNER TO postgres;

--
-- Name: case_limit_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.case_limit_id_seq OWNED BY public.case_limit.id;


--
-- Name: case_remarks_multiple; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.case_remarks_multiple (
    cl_date date NOT NULL,
    r_head smallint DEFAULT '0'::smallint NOT NULL,
    head_content character varying(200) NOT NULL,
    remark text NOT NULL,
    e_date timestamp with time zone,
    jcodes character varying(50),
    remove smallint DEFAULT '0'::smallint,
    mainhead character(1) NOT NULL,
    clno smallint NOT NULL,
    uid bigint,
    dw character(1) NOT NULL,
    status character(1) NOT NULL,
    usr_entry bigint NOT NULL,
    comp_date date,
    notice_type bigint NOT NULL,
    comp_comp_date date,
    comp_remarks character varying(150) NOT NULL,
    last_updated timestamp with time zone,
    diary_no character varying(100)
);


ALTER TABLE public.case_remarks_multiple OWNER TO postgres;

--
-- Name: case_remarks_multiple_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.case_remarks_multiple_a (
    diary_no bigint,
    cl_date date,
    r_head smallint,
    head_content character varying(200),
    remark text,
    e_date timestamp with time zone,
    jcodes character varying(50),
    remove smallint,
    mainhead character(1),
    clno smallint,
    uid bigint,
    dw character(1),
    status character(1),
    usr_entry bigint,
    comp_date date,
    notice_type bigint,
    comp_comp_date date,
    comp_remarks character varying(150),
    last_updated timestamp with time zone
);


ALTER TABLE public.case_remarks_multiple_a OWNER TO dev;

--
-- Name: case_remarks_multiple_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.case_remarks_multiple_history (
    fil_no character varying(14) DEFAULT ''::character varying NOT NULL,
    cl_date date,
    r_head smallint DEFAULT '0'::smallint NOT NULL,
    head_content character varying(100) NOT NULL,
    remark text NOT NULL,
    e_date timestamp with time zone,
    jcodes character varying(25) NOT NULL,
    remove smallint DEFAULT '0'::smallint,
    mainhead character(1) NOT NULL,
    clno smallint NOT NULL,
    uid bigint,
    dw character(1) NOT NULL,
    status character(1) NOT NULL,
    usr_entry bigint NOT NULL,
    comp_date date,
    notice_type bigint NOT NULL,
    comp_comp_date date,
    comp_remarks character varying(150) NOT NULL,
    last_updated timestamp with time zone
);


ALTER TABLE public.case_remarks_multiple_history OWNER TO postgres;

--
-- Name: case_remarks_multiple_history_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.case_remarks_multiple_history_a (
    fil_no character varying(14),
    cl_date date,
    r_head smallint,
    head_content character varying(100),
    remark text,
    e_date timestamp with time zone,
    jcodes character varying(25),
    remove smallint,
    mainhead character(1),
    clno smallint,
    uid bigint,
    dw character(1),
    status character(1),
    usr_entry bigint,
    comp_date date,
    notice_type bigint,
    comp_comp_date date,
    comp_remarks character varying(150),
    last_updated timestamp with time zone
);


ALTER TABLE public.case_remarks_multiple_history_a OWNER TO dev;

--
-- Name: case_remarks_verification; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.case_remarks_verification (
    id bigint NOT NULL,
    diary_no bigint,
    cl_date date,
    status character(1),
    approved_by bigint,
    approved_on timestamp with time zone,
    rejection_remark character varying(100),
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE public.case_remarks_verification OWNER TO postgres;

--
-- Name: case_remarks_verification_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.case_remarks_verification_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.case_remarks_verification_id_seq OWNER TO postgres;

--
-- Name: case_remarks_verification_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.case_remarks_verification_id_seq OWNED BY public.case_remarks_verification.id;


--
-- Name: case_section_mapping; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.case_section_mapping (
    diary_no bigint,
    section_id bigint
);


ALTER TABLE public.case_section_mapping OWNER TO postgres;

--
-- Name: case_verify; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.case_verify (
    diary_no bigint NOT NULL,
    next_dt date,
    m_f character(1) NOT NULL,
    board_type character(1) NOT NULL,
    ent_dt timestamp with time zone,
    ucode bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    id bigint NOT NULL,
    remark_id character varying(60)
);


ALTER TABLE public.case_verify OWNER TO postgres;

--
-- Name: case_verify_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.case_verify_a (
    diary_no bigint,
    next_dt date,
    m_f character(1),
    board_type character(1),
    ent_dt timestamp with time zone,
    ucode bigint,
    display character(1),
    id bigint,
    remark_id character varying(60)
);


ALTER TABLE public.case_verify_a OWNER TO dev;

--
-- Name: case_verify_by_sec; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.case_verify_by_sec (
    diary_no bigint NOT NULL,
    next_dt date,
    m_f character(1) NOT NULL,
    board_type character(1) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    bo_ent_dt timestamp with time zone,
    bo_ucode bigint NOT NULL,
    ar_ent_dt timestamp with time zone,
    ar_ucode bigint NOT NULL,
    dy_ent_dt timestamp with time zone,
    dy_ucode bigint NOT NULL,
    adr_ent_dt timestamp with time zone,
    adr_ucode bigint NOT NULL,
    remark bigint,
    remark_ar bigint,
    remark_dy bigint
);


ALTER TABLE public.case_verify_by_sec OWNER TO postgres;

--
-- Name: case_verify_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.case_verify_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.case_verify_id_seq OWNER TO postgres;

--
-- Name: case_verify_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.case_verify_id_seq OWNED BY public.case_verify.id;


--
-- Name: case_verify_rop; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.case_verify_rop (
    diary_no bigint NOT NULL,
    cl_dt date,
    m_f character(1) NOT NULL,
    board_type character(1) NOT NULL,
    ent_dt timestamp with time zone,
    ucode bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    remark_id character varying(60),
    tentative_dt date,
    court bigint,
    id bigint NOT NULL
);


ALTER TABLE public.case_verify_rop OWNER TO postgres;

--
-- Name: case_verify_rop_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.case_verify_rop_a (
    diary_no bigint,
    cl_dt date,
    m_f character(1),
    board_type character(1),
    ent_dt timestamp with time zone,
    ucode bigint,
    display character(1),
    remark_id character varying(60),
    tentative_dt date,
    court bigint,
    id bigint
);


ALTER TABLE public.case_verify_rop_a OWNER TO dev;

--
-- Name: case_verify_rop_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.case_verify_rop_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.case_verify_rop_id_seq OWNER TO postgres;

--
-- Name: case_verify_rop_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.case_verify_rop_id_seq OWNED BY public.case_verify_rop.id;


--
-- Name: category_allottment; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.category_allottment (
    cat_allot_id bigint NOT NULL,
    stage_code bigint NOT NULL,
    stage_nature character(5) NOT NULL,
    ros_id bigint NOT NULL,
    priority bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    case_type bigint NOT NULL,
    submaster_id bigint NOT NULL,
    b_n character varying(1) NOT NULL
);


ALTER TABLE public.category_allottment OWNER TO postgres;

--
-- Name: category_allottment_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.category_allottment_a (
    cat_allot_id bigint,
    stage_code bigint,
    stage_nature character(5),
    ros_id bigint,
    priority bigint,
    display character varying(1),
    case_type bigint,
    submaster_id bigint,
    b_n character varying(1)
);


ALTER TABLE public.category_allottment_a OWNER TO dev;

--
-- Name: category_allottment_cat_allot_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.category_allottment_cat_allot_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.category_allottment_cat_allot_id_seq OWNER TO postgres;

--
-- Name: category_allottment_cat_allot_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.category_allottment_cat_allot_id_seq OWNED BY public.category_allottment.cat_allot_id;


--
-- Name: cause_title; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cause_title (
    cause_title_id bigint NOT NULL,
    diary_no bigint NOT NULL,
    path character varying(250) NOT NULL,
    created_on timestamp with time zone,
    created_by bigint NOT NULL,
    created_ip character varying(16),
    updated_on timestamp with time zone,
    updated_by bigint,
    updated_ip character varying(20),
    is_active smallint DEFAULT '1'::smallint NOT NULL
);


ALTER TABLE public.cause_title OWNER TO postgres;

--
-- Name: cause_title_cause_title_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cause_title_cause_title_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cause_title_cause_title_id_seq OWNER TO postgres;

--
-- Name: cause_title_cause_title_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cause_title_cause_title_id_seq OWNED BY public.cause_title.cause_title_id;


--
-- Name: causelist_file_movement; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.causelist_file_movement (
    id bigint NOT NULL,
    diary_no bigint,
    next_dt date,
    roster_id bigint,
    dacode bigint,
    cm_nsh_usercode bigint,
    ref_file_movement_status_id bigint,
    updated_on timestamp with time zone,
    usercode bigint
);


ALTER TABLE public.causelist_file_movement OWNER TO postgres;

--
-- Name: causelist_file_movement_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.causelist_file_movement_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.causelist_file_movement_id_seq OWNER TO postgres;

--
-- Name: causelist_file_movement_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.causelist_file_movement_id_seq OWNED BY public.causelist_file_movement.id;


--
-- Name: causelist_file_movement_transactions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.causelist_file_movement_transactions (
    id bigint NOT NULL,
    causelist_file_movement_id bigint,
    ref_file_movement_status_id bigint,
    attendant_usercode bigint,
    remarks character varying(100),
    usercode bigint,
    updated_on timestamp with time zone
);


ALTER TABLE public.causelist_file_movement_transactions OWNER TO postgres;

--
-- Name: causelist_file_movement_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.causelist_file_movement_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.causelist_file_movement_transactions_id_seq OWNER TO postgres;

--
-- Name: causelist_file_movement_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.causelist_file_movement_transactions_id_seq OWNED BY public.causelist_file_movement_transactions.id;


--
-- Name: caveat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.caveat (
    caveat_no bigint DEFAULT '0'::bigint NOT NULL,
    fil_no character varying(16),
    pet_name character varying(100),
    res_name character varying(100),
    pet_adv_id bigint NOT NULL,
    res_adv_id bigint NOT NULL,
    actcode bigint,
    claim_amt bigint DEFAULT 0 NOT NULL,
    bench bigint,
    fixed character(2),
    c_status character(1),
    fil_dt timestamp with time zone,
    case_pages bigint DEFAULT 0 NOT NULL,
    relief character varying(150),
    usercode bigint,
    last_usercode bigint,
    dacode bigint,
    last_dt timestamp with time zone,
    conn_key character varying(10),
    case_grp character(3),
    lastorder text,
    fixeddet text,
    bailno character varying(1),
    prevno character varying(14),
    head_code character varying(50),
    scr_user bigint NOT NULL,
    scr_time timestamp with time zone,
    scr_type character varying(2) NOT NULL,
    prevno_fildt timestamp with time zone,
    ack_id bigint NOT NULL,
    ack_rec_dt character varying(4) NOT NULL,
    admitted character varying(60),
    outside character(1) DEFAULT 'N'::bpchar NOT NULL,
    diary_no_rec_date timestamp with time zone,
    diary_user_id bigint NOT NULL,
    ref_agency_state_id bigint NOT NULL,
    ref_agency_code_id bigint NOT NULL,
    from_court bigint NOT NULL,
    is_undertaking character varying(1),
    undertaking_doc_type bigint NOT NULL,
    undertaking_reason character varying(100) NOT NULL,
    casetype_id bigint NOT NULL,
    casetype_name character varying(20) NOT NULL,
    padvt character(2) NOT NULL,
    radvt character(2) NOT NULL,
    total_court_fee bigint NOT NULL,
    court_fee bigint NOT NULL,
    valuation bigint NOT NULL,
    case_status_id bigint NOT NULL,
    brief_description character varying(500) NOT NULL,
    nature character varying(1) NOT NULL,
    fil_no_fh character varying(16) NOT NULL,
    fil_dt_fh timestamp with time zone,
    mf_active character(1) NOT NULL,
    pno bigint NOT NULL,
    rno bigint NOT NULL,
    is_renew character(1)
);


ALTER TABLE public.caveat OWNER TO postgres;

--
-- Name: caveat_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.caveat_a (
    caveat_no bigint,
    fil_no character varying(16),
    pet_name character varying(100),
    res_name character varying(100),
    pet_adv_id bigint,
    res_adv_id bigint,
    actcode bigint,
    claim_amt bigint,
    bench bigint,
    fixed character(2),
    c_status character(1),
    fil_dt timestamp with time zone,
    case_pages bigint,
    relief character varying(150),
    usercode bigint,
    last_usercode bigint,
    dacode bigint,
    last_dt timestamp with time zone,
    conn_key character varying(10),
    case_grp character(3),
    lastorder text,
    fixeddet text,
    bailno character varying(1),
    prevno character varying(14),
    head_code character varying(50),
    scr_user bigint,
    scr_time timestamp with time zone,
    scr_type character varying(2),
    prevno_fildt timestamp with time zone,
    ack_id bigint,
    ack_rec_dt character varying(4),
    admitted character varying(60),
    outside character(1),
    diary_no_rec_date timestamp with time zone,
    diary_user_id bigint,
    ref_agency_state_id bigint,
    ref_agency_code_id bigint,
    from_court bigint,
    is_undertaking character varying(1),
    undertaking_doc_type bigint,
    undertaking_reason character varying(100),
    casetype_id bigint,
    casetype_name character varying(20),
    padvt character(2),
    radvt character(2),
    total_court_fee bigint,
    court_fee bigint,
    valuation bigint,
    case_status_id bigint,
    brief_description character varying(500),
    nature character varying(1),
    fil_no_fh character varying(16),
    fil_dt_fh timestamp with time zone,
    mf_active character(1),
    pno bigint,
    rno bigint,
    is_renew character(1)
);


ALTER TABLE public.caveat_a OWNER TO dev;

--
-- Name: caveat_adv; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.caveat_adv (
    cav_no bigint NOT NULL,
    cav_yr bigint NOT NULL,
    adv_en character varying(6) NOT NULL,
    adv_yr bigint NOT NULL,
    adv_name character varying(100) NOT NULL,
    usercode bigint NOT NULL,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE public.caveat_adv OWNER TO postgres;

--
-- Name: caveat_advocate; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.caveat_advocate (
    caveat_no bigint,
    adv_type character(1),
    pet_res character(1),
    pet_res_no bigint,
    advocate_id bigint,
    adv character varying(60),
    usercode numeric(4,0),
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar,
    stateadv character(1) DEFAULT 'N'::bpchar,
    old_adv character varying(200) NOT NULL
);


ALTER TABLE public.caveat_advocate OWNER TO postgres;

--
-- Name: caveat_advocate_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.caveat_advocate_a (
    caveat_no bigint,
    adv_type character(1),
    pet_res character(1),
    pet_res_no bigint,
    advocate_id bigint,
    adv character varying(60),
    usercode numeric(4,0),
    ent_dt timestamp with time zone,
    display character(1),
    stateadv character(1),
    old_adv character varying(200)
);


ALTER TABLE public.caveat_advocate_a OWNER TO dev;

--
-- Name: caveat_diary_matching; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.caveat_diary_matching (
    caveat_no bigint NOT NULL,
    diary_no bigint NOT NULL,
    link_dt timestamp with time zone,
    usercode bigint NOT NULL,
    caveat_diary character(1) NOT NULL,
    ent_dt timestamp with time zone,
    matching_reason character varying(25) NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    notice_path character varying(100) NOT NULL,
    print_dt timestamp with time zone,
    print_user_id bigint NOT NULL
);


ALTER TABLE public.caveat_diary_matching OWNER TO postgres;

--
-- Name: caveat_diary_matching_new; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.caveat_diary_matching_new (
    caveat_no character varying(14),
    diary_no character varying(14) DEFAULT ''::character varying NOT NULL,
    link_dt timestamp with time zone,
    usercode bigint DEFAULT '0'::bigint NOT NULL,
    c_d character varying(1) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.caveat_diary_matching_new OWNER TO postgres;

--
-- Name: caveat_lowerct; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.caveat_lowerct (
    caveat_no bigint NOT NULL,
    lct_dec_dt date,
    lct_judge_desg bigint,
    lct_judge_name character varying(50),
    lctjudname2 character varying(50),
    lct_jud_id character varying(100) NOT NULL,
    lct_jud_id1 bigint NOT NULL,
    lct_jud_id2 bigint NOT NULL,
    lct_jud_id3 bigint NOT NULL,
    l_dist bigint,
    polstncode bigint,
    crimeno bigint,
    crimeyear bigint,
    usercode bigint,
    ent_dt timestamp with time zone,
    lctjudname3 character varying(50) NOT NULL,
    ct_code bigint NOT NULL,
    doi date,
    hjs_cnr character varying(16) NOT NULL,
    ljs_doi date,
    ljs_cnr character varying(16) NOT NULL,
    l_state bigint NOT NULL,
    l_state_old bigint NOT NULL,
    lower_court_id bigint NOT NULL,
    lw_display character varying(1) NOT NULL,
    brief_desc character varying(200),
    sub_law character varying(200),
    l_inddep character(2) NOT NULL,
    l_iopb bigint,
    l_iopbn character varying(100),
    l_org bigint NOT NULL,
    l_orgname character varying(100) NOT NULL,
    l_ordchno character varying(50) NOT NULL,
    lct_casetype bigint NOT NULL,
    lct_casetype_old bigint,
    lct_caseno character varying(50),
    lct_caseyear bigint NOT NULL,
    is_order_challenged character varying(1) NOT NULL,
    full_interim_flag character varying(1) NOT NULL,
    judgement_covered_in character varying(250) NOT NULL,
    vehicle_code bigint NOT NULL,
    vehicle_no character varying(7) NOT NULL,
    cnr_no character varying(16) NOT NULL,
    ref_court bigint NOT NULL,
    ref_case_type bigint NOT NULL,
    ref_case_no bigint NOT NULL,
    ref_case_year bigint NOT NULL,
    ref_state bigint NOT NULL,
    ref_district bigint NOT NULL,
    gov_not_state_id bigint NOT NULL,
    gov_not_case_type character varying(50),
    gov_not_case_no bigint NOT NULL,
    gov_not_case_year bigint NOT NULL,
    gov_not_date date
);


ALTER TABLE public.caveat_lowerct OWNER TO postgres;

--
-- Name: caveat_lowerct_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.caveat_lowerct_a (
    caveat_no bigint,
    lct_dec_dt date,
    lct_judge_desg bigint,
    lct_judge_name character varying(50),
    lctjudname2 character varying(50),
    lct_jud_id character varying(100),
    lct_jud_id1 bigint,
    lct_jud_id2 bigint,
    lct_jud_id3 bigint,
    l_dist bigint,
    polstncode bigint,
    crimeno bigint,
    crimeyear bigint,
    usercode bigint,
    ent_dt timestamp with time zone,
    lctjudname3 character varying(50),
    ct_code bigint,
    doi date,
    hjs_cnr character varying(16),
    ljs_doi date,
    ljs_cnr character varying(16),
    l_state bigint,
    l_state_old bigint,
    lower_court_id bigint,
    lw_display character varying(1),
    brief_desc character varying(200),
    sub_law character varying(200),
    l_inddep character(2),
    l_iopb bigint,
    l_iopbn character varying(100),
    l_org bigint,
    l_orgname character varying(100),
    l_ordchno character varying(50),
    lct_casetype bigint,
    lct_casetype_old bigint,
    lct_caseno character varying(50),
    lct_caseyear bigint,
    is_order_challenged character varying(1),
    full_interim_flag character varying(1),
    judgement_covered_in character varying(250),
    vehicle_code bigint,
    vehicle_no character varying(7),
    cnr_no character varying(16),
    ref_court bigint,
    ref_case_type bigint,
    ref_case_no bigint,
    ref_case_year bigint,
    ref_state bigint,
    ref_district bigint,
    gov_not_state_id bigint,
    gov_not_case_type character varying(50),
    gov_not_case_no bigint,
    gov_not_case_year bigint,
    gov_not_date date
);


ALTER TABLE public.caveat_lowerct_a OWNER TO dev;

--
-- Name: caveat_lowerct_judges; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.caveat_lowerct_judges (
    id bigint NOT NULL,
    lowerct_id bigint NOT NULL,
    judge_id bigint NOT NULL,
    lct_display character varying(1) DEFAULT 'Y'::character varying NOT NULL
);


ALTER TABLE public.caveat_lowerct_judges OWNER TO postgres;

--
-- Name: caveat_lowerct_judges_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.caveat_lowerct_judges_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.caveat_lowerct_judges_id_seq OWNER TO postgres;

--
-- Name: caveat_lowerct_judges_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.caveat_lowerct_judges_id_seq OWNED BY public.caveat_lowerct_judges.id;


--
-- Name: caveat_lowerct_lower_court_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.caveat_lowerct_lower_court_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.caveat_lowerct_lower_court_id_seq OWNER TO postgres;

--
-- Name: caveat_lowerct_lower_court_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.caveat_lowerct_lower_court_id_seq OWNED BY public.caveat_lowerct.lower_court_id;


--
-- Name: caveat_party; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.caveat_party (
    sr_no bigint,
    caveat_no bigint NOT NULL,
    pet_res character(1) DEFAULT 'P'::bpchar NOT NULL,
    sr_no_old bigint DEFAULT 0,
    ind_dep character(2) DEFAULT 'I'::bpchar NOT NULL,
    partysuff character varying(100),
    partyname character varying(200),
    sonof character(1) DEFAULT 'S'::bpchar NOT NULL,
    authcode bigint DEFAULT 0 NOT NULL,
    state_in_name bigint NOT NULL,
    prfhname character varying(100),
    age bigint DEFAULT 0,
    sex character(1),
    caste character varying(10),
    addr1 character varying(300),
    addr2 character varying(300),
    state character varying(15),
    city character varying(15),
    pin bigint,
    email character varying(50),
    contact character varying(15),
    usercode bigint,
    ent_dt timestamp with time zone,
    pflag character(1) DEFAULT 'P'::bpchar NOT NULL,
    dstname character varying(30) NOT NULL,
    deptcode bigint DEFAULT 0,
    pan_card character varying(10) NOT NULL,
    adhar_card character varying(12) NOT NULL,
    country bigint NOT NULL,
    education character varying(50) NOT NULL,
    occ_code bigint NOT NULL,
    edu_code bigint NOT NULL,
    lowercase_id bigint NOT NULL
);


ALTER TABLE public.caveat_party OWNER TO postgres;

--
-- Name: caveat_party_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.caveat_party_a (
    sr_no bigint,
    caveat_no bigint,
    pet_res character(1),
    sr_no_old bigint,
    ind_dep character(2),
    partysuff character varying(100),
    partyname character varying(200),
    sonof character(1),
    authcode bigint,
    state_in_name bigint,
    prfhname character varying(100),
    age bigint,
    sex character(1),
    caste character varying(10),
    addr1 character varying(300),
    addr2 character varying(300),
    state character varying(15),
    city character varying(15),
    pin bigint,
    email character varying(50),
    contact character varying(15),
    usercode bigint,
    ent_dt timestamp with time zone,
    pflag character(1),
    dstname character varying(30),
    deptcode bigint,
    pan_card character varying(10),
    adhar_card character varying(12),
    country bigint,
    education character varying(50),
    occ_code bigint,
    edu_code bigint,
    lowercase_id bigint
);


ALTER TABLE public.caveat_party_a OWNER TO dev;

--
-- Name: change_fil_dt; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.change_fil_dt (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    fil_no character varying(16),
    fil_dt timestamp with time zone,
    order_date timestamp with time zone,
    c_status character(1)
);


ALTER TABLE public.change_fil_dt OWNER TO postgres;

--
-- Name: chk_case; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.chk_case (
    id bigint NOT NULL,
    chkcode bigint NOT NULL,
    casecode bigint NOT NULL,
    bailno bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    usercode bigint NOT NULL,
    ent_dt timestamp with time zone
);


ALTER TABLE public.chk_case OWNER TO postgres;

--
-- Name: chk_case_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.chk_case_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.chk_case_id_seq OWNER TO postgres;

--
-- Name: chk_case_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.chk_case_id_seq OWNED BY public.chk_case.id;


--
-- Name: cl_freezed; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cl_freezed (
    id bigint NOT NULL,
    next_dt date,
    m_f character(1) NOT NULL,
    part bigint NOT NULL,
    board_type character(2) DEFAULT '0'::bpchar,
    freezed_by bigint DEFAULT '0'::bigint NOT NULL,
    freezed_on timestamp with time zone,
    freezed_by_ip character varying(20) DEFAULT '0'::character varying NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    unfreezed_by bigint NOT NULL,
    unfreezed_on timestamp with time zone,
    unfreezed_by_ip character varying(20) DEFAULT '0'::character varying NOT NULL
);


ALTER TABLE public.cl_freezed OWNER TO postgres;

--
-- Name: cl_freezed_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cl_freezed_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cl_freezed_id_seq OWNER TO postgres;

--
-- Name: cl_freezed_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cl_freezed_id_seq OWNED BY public.cl_freezed.id;


--
-- Name: cl_gen; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cl_gen (
    id bigint NOT NULL,
    cl_date date,
    user_id bigint NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    date_of_gen timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.cl_gen OWNER TO postgres;

--
-- Name: cl_gen_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cl_gen_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cl_gen_id_seq OWNER TO postgres;

--
-- Name: cl_gen_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cl_gen_id_seq OWNED BY public.cl_gen.id;


--
-- Name: cl_printed; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cl_printed (
    id bigint NOT NULL,
    next_dt date,
    m_f character(1) NOT NULL,
    part bigint NOT NULL,
    main_supp bigint DEFAULT 0,
    from_brd_no bigint DEFAULT 0 NOT NULL,
    to_brd_no bigint DEFAULT 0 NOT NULL,
    roster_id bigint DEFAULT '0'::bigint,
    usercode bigint DEFAULT '0'::bigint NOT NULL,
    ent_time timestamp with time zone,
    user_ip character varying(20) DEFAULT '0'::character varying NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    deleted_by bigint NOT NULL,
    deleted_on timestamp with time zone,
    pdf_gen timestamp with time zone,
    pdf_nm character varying(200) NOT NULL,
    pdf_dtl_nm character varying(200) NOT NULL,
    pdf_dtl_dt timestamp with time zone,
    is_pdf_murge character(1) DEFAULT 'N'::bpchar NOT NULL,
    pdf_murge_time timestamp with time zone
);


ALTER TABLE public.cl_printed OWNER TO postgres;

--
-- Name: cl_printed_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cl_printed_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cl_printed_id_seq OWNER TO postgres;

--
-- Name: cl_printed_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cl_printed_id_seq OWNED BY public.cl_printed.id;


--
-- Name: cl_text_save; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cl_text_save (
    clp_id bigint NOT NULL,
    cl_content text NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    userid bigint NOT NULL,
    ent_dt timestamp with time zone
);


ALTER TABLE public.cl_text_save OWNER TO postgres;

--
-- Name: conct; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.conct (
    conn_key bigint,
    diary_no bigint,
    list character(1) DEFAULT 'N'::bpchar NOT NULL,
    usercode bigint,
    ent_dt timestamp with time zone,
    conn_type character(1) NOT NULL,
    linked_to bigint NOT NULL,
    linking_reason character varying(500) NOT NULL,
    migration bigint NOT NULL
);


ALTER TABLE public.conct OWNER TO postgres;

--
-- Name: conct_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.conct_a (
    conn_key bigint,
    diary_no bigint,
    list character(1),
    usercode bigint,
    ent_dt timestamp with time zone,
    conn_type character(1),
    linked_to bigint,
    linking_reason character varying(500),
    migration bigint
);


ALTER TABLE public.conct_a OWNER TO dev;

--
-- Name: conct_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.conct_history (
    conn_key bigint,
    diary_no bigint,
    list character(1) DEFAULT 'N'::bpchar NOT NULL,
    usercode bigint,
    ent_dt timestamp with time zone,
    conn_type character(1) NOT NULL,
    linked_to bigint NOT NULL,
    linking_reason character varying(500),
    migration bigint NOT NULL,
    chng_by bigint NOT NULL,
    chng_date timestamp with time zone
);


ALTER TABLE public.conct_history OWNER TO postgres;

--
-- Name: conct_history_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.conct_history_a (
    conn_key bigint,
    diary_no bigint,
    list character(1),
    usercode bigint,
    ent_dt timestamp with time zone,
    conn_type character(1),
    linked_to bigint,
    linking_reason character varying(500),
    migration bigint,
    chng_by bigint,
    chng_date timestamp with time zone
);


ALTER TABLE public.conct_history_a OWNER TO dev;

--
-- Name: consent_through_email; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.consent_through_email (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint NOT NULL,
    next_dt date,
    roster_id bigint,
    part bigint NOT NULL,
    main_supp_flag smallint NOT NULL,
    applicant_type smallint DEFAULT '1'::smallint,
    party_id bigint,
    advocate_id bigint,
    entry_source smallint DEFAULT '1'::smallint NOT NULL,
    user_id bigint,
    entry_date timestamp with time zone,
    user_ip character varying(45),
    is_deleted smallint,
    deleted_by bigint,
    deleted_on timestamp with time zone,
    deleted_ip character varying(45)
);


ALTER TABLE public.consent_through_email OWNER TO postgres;

--
-- Name: consent_through_email_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.consent_through_email_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.consent_through_email_id_seq OWNER TO postgres;

--
-- Name: consent_through_email_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.consent_through_email_id_seq OWNED BY public.consent_through_email.id;


--
-- Name: copying_application_defects; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.copying_application_defects (
    id bigint NOT NULL,
    copying_order_issuing_application_id bigint NOT NULL,
    ref_order_defect_id bigint NOT NULL,
    defect_notification_date timestamp with time zone,
    defect_cure_date timestamp with time zone,
    defect_notified_by bigint,
    defect_cured_by bigint,
    remark character varying(100) NOT NULL
);


ALTER TABLE public.copying_application_defects OWNER TO postgres;

--
-- Name: copying_application_defects_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.copying_application_defects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.copying_application_defects_id_seq OWNER TO postgres;

--
-- Name: copying_application_defects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.copying_application_defects_id_seq OWNED BY public.copying_application_defects.id;


--
-- Name: copying_application_defects_org; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.copying_application_defects_org (
    id bigint NOT NULL,
    copying_order_issuing_application_id bigint NOT NULL,
    ref_order_defect_id bigint NOT NULL,
    defect_notification_date timestamp with time zone,
    defect_cure_date timestamp with time zone,
    defect_notified_by bigint,
    defect_cured_by bigint
);


ALTER TABLE public.copying_application_defects_org OWNER TO postgres;

--
-- Name: copying_application_defects_org_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.copying_application_defects_org_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.copying_application_defects_org_id_seq OWNER TO postgres;

--
-- Name: copying_application_defects_org_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.copying_application_defects_org_id_seq OWNED BY public.copying_application_defects_org.id;


--
-- Name: copying_application_documents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.copying_application_documents (
    id bigint NOT NULL,
    order_type bigint NOT NULL,
    order_date timestamp with time zone,
    copying_order_issuing_application_id bigint NOT NULL,
    number_of_copies smallint DEFAULT '1'::smallint,
    number_of_pages_in_pdf bigint NOT NULL,
    path text,
    from_page bigint,
    to_page bigint,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    pdf_embed_path character varying(100),
    pdf_embed_on timestamp with time zone,
    pdf_embed_by bigint,
    pdf_downloaded_on timestamp with time zone,
    pdf_downloaded_by bigint,
    pdf_digital_signature_path character varying(100),
    pdf_digital_signature_on timestamp with time zone,
    pdf_digital_signature_by bigint,
    sent_to_applicant_on timestamp with time zone,
    sent_to_applicant_by bigint,
    email_sent_on timestamp with time zone,
    is_bail_order character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.copying_application_documents OWNER TO postgres;

--
-- Name: copying_application_documents_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.copying_application_documents_a (
    id bigint,
    order_type bigint,
    order_date timestamp with time zone,
    copying_order_issuing_application_id bigint,
    number_of_copies smallint,
    number_of_pages_in_pdf bigint,
    path text,
    from_page bigint,
    to_page bigint,
    display character(1),
    pdf_embed_path character varying(100),
    pdf_embed_on timestamp with time zone,
    pdf_embed_by bigint,
    pdf_downloaded_on timestamp with time zone,
    pdf_downloaded_by bigint,
    pdf_digital_signature_path character varying(100),
    pdf_digital_signature_on timestamp with time zone,
    pdf_digital_signature_by bigint,
    sent_to_applicant_on timestamp with time zone,
    sent_to_applicant_by bigint,
    email_sent_on timestamp with time zone,
    is_bail_order character(1)
);


ALTER TABLE public.copying_application_documents_a OWNER TO dev;

--
-- Name: copying_application_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.copying_application_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.copying_application_documents_id_seq OWNER TO postgres;

--
-- Name: copying_application_documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.copying_application_documents_id_seq OWNED BY public.copying_application_documents.id;


--
-- Name: copying_application_documents_org; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.copying_application_documents_org (
    id bigint NOT NULL,
    order_type bigint NOT NULL,
    order_date timestamp with time zone,
    copying_order_issuing_application_id bigint NOT NULL,
    number_of_copies smallint DEFAULT '1'::smallint NOT NULL
);


ALTER TABLE public.copying_application_documents_org OWNER TO postgres;

--
-- Name: copying_application_documents_org_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.copying_application_documents_org_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.copying_application_documents_org_id_seq OWNER TO postgres;

--
-- Name: copying_application_documents_org_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.copying_application_documents_org_id_seq OWNED BY public.copying_application_documents_org.id;


--
-- Name: copying_order_issuing_application_new; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.copying_order_issuing_application_new (
    id bigint NOT NULL,
    diary bigint,
    copy_category bigint NOT NULL,
    application_reg_number bigint NOT NULL,
    application_reg_year smallint NOT NULL,
    application_receipt timestamp with time zone,
    advocate_or_party character varying(100),
    court_fee numeric(10,0),
    delivery_mode character(1),
    postal_fee numeric(10,0),
    ready_date timestamp with time zone,
    dispatch_delivery_date timestamp with time zone,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted boolean DEFAULT false NOT NULL,
    is_id_checked boolean DEFAULT false,
    purpose character varying(500),
    application_status character(1) DEFAULT 'P'::bpchar,
    defect_code bigint,
    defect_description character varying(500),
    notification_date timestamp with time zone,
    filed_by smallint,
    name character varying(45),
    mobile character varying(10),
    address character varying(300),
    application_number_display character varying(45),
    temp_id character varying(6),
    remarks character varying(200),
    source bigint NOT NULL,
    send_to_section character varying(2) DEFAULT 'f'::character varying NOT NULL,
    crn character varying(50) DEFAULT '0'::character varying NOT NULL,
    email character varying(100),
    authorized_by_aor bigint DEFAULT '0'::bigint NOT NULL,
    allowed_request character varying(45) DEFAULT '0'::character varying NOT NULL,
    ready_remarks character varying(45)
);


ALTER TABLE public.copying_order_issuing_application_new OWNER TO postgres;

--
-- Name: copying_order_issuing_application_new_duplicate; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.copying_order_issuing_application_new_duplicate (
    id numeric DEFAULT '0'::numeric NOT NULL,
    diary bigint,
    copy_category bigint,
    application_reg_number bigint,
    application_reg_year smallint,
    application_receipt timestamp with time zone,
    advocate_or_party character varying(100),
    court_fee numeric(10,0),
    delivery_mode character(1),
    postal_fee numeric(10,0),
    ready_date timestamp with time zone,
    dispatch_delivery_date timestamp with time zone,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted boolean DEFAULT false NOT NULL,
    is_id_checked boolean DEFAULT false,
    purpose character varying(500),
    application_status character(1) DEFAULT 'P'::bpchar,
    defect_code bigint,
    defect_description character varying(500),
    notification_date timestamp with time zone,
    filed_by smallint,
    name character varying(45),
    mobile character varying(10),
    address character varying(300),
    application_number_display character varying(45),
    temp_id character varying(6),
    remarks character varying(200),
    source bigint NOT NULL,
    send_to_section character varying(2) DEFAULT 'f'::character varying NOT NULL
);


ALTER TABLE public.copying_order_issuing_application_new_duplicate OWNER TO postgres;

--
-- Name: copying_order_issuing_application_new_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.copying_order_issuing_application_new_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.copying_order_issuing_application_new_id_seq OWNER TO postgres;

--
-- Name: copying_order_issuing_application_new_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.copying_order_issuing_application_new_id_seq OWNED BY public.copying_order_issuing_application_new.id;


--
-- Name: copying_order_issuing_application_new_org; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.copying_order_issuing_application_new_org (
    id bigint NOT NULL,
    diary bigint,
    copy_category character(2),
    application_reg_number character varying(7),
    application_reg_year smallint,
    application_receipt timestamp with time zone,
    advocate_or_party character varying(100),
    court_fee numeric(10,0),
    delivery_mode character(1),
    postal_fee numeric(10,0),
    ready_date timestamp with time zone,
    dispatch_delivery_date timestamp with time zone,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted boolean DEFAULT false NOT NULL,
    is_id_checked boolean DEFAULT false,
    purpose character varying(500),
    application_status character(1) DEFAULT 'P'::bpchar,
    defect_code bigint,
    defect_description character varying(500),
    notification_date timestamp with time zone,
    filed_by smallint,
    name character varying(45),
    mobile character varying(10),
    address character varying(300),
    application_number_display character varying(45)
);


ALTER TABLE public.copying_order_issuing_application_new_org OWNER TO postgres;

--
-- Name: copying_order_issuing_application_new_org_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.copying_order_issuing_application_new_org_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.copying_order_issuing_application_new_org_id_seq OWNER TO postgres;

--
-- Name: copying_order_issuing_application_new_org_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.copying_order_issuing_application_new_org_id_seq OWNED BY public.copying_order_issuing_application_new_org.id;


--
-- Name: copying_request_movement; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.copying_request_movement (
    id bigint NOT NULL,
    copying_request_verify_documents_id bigint NOT NULL,
    from_section bigint,
    from_section_sent_by bigint,
    from_section_sent_on timestamp with time zone,
    to_section bigint,
    remark character varying(100),
    display character(1) DEFAULT 'Y'::bpchar,
    deleted_on timestamp with time zone,
    deleted_by bigint
);


ALTER TABLE public.copying_request_movement OWNER TO postgres;

--
-- Name: copying_request_movement_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.copying_request_movement_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.copying_request_movement_id_seq OWNER TO postgres;

--
-- Name: copying_request_movement_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.copying_request_movement_id_seq OWNED BY public.copying_request_movement.id;


--
-- Name: copying_request_verify; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.copying_request_verify (
    id bigint NOT NULL,
    diary bigint,
    copy_category bigint NOT NULL,
    application_reg_number bigint NOT NULL,
    application_reg_year smallint NOT NULL,
    application_receipt timestamp with time zone,
    advocate_or_party character varying(100),
    court_fee numeric(10,0),
    delivery_mode character(1),
    postal_fee numeric(10,2),
    ready_date timestamp with time zone,
    dispatch_delivery_date timestamp with time zone,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted boolean DEFAULT false NOT NULL,
    is_id_checked boolean DEFAULT false,
    purpose character varying(500),
    application_status character(1) DEFAULT 'P'::bpchar,
    defect_code bigint,
    defect_description character varying(500),
    notification_date timestamp with time zone,
    filed_by smallint,
    name character varying(45),
    mobile character varying(10),
    address character varying(300),
    application_number_display character varying(45),
    temp_id character varying(6),
    remarks character varying(200),
    source bigint NOT NULL,
    send_to_section character varying(2) DEFAULT 'f'::character varying NOT NULL,
    crn character varying(50) DEFAULT '0'::character varying NOT NULL,
    email character varying(100),
    authorized_by_aor bigint DEFAULT '0'::bigint NOT NULL,
    allowed_request character varying(45) DEFAULT '0'::character varying NOT NULL,
    ready_remarks character varying(45),
    token_id character varying(50),
    address_id bigint,
    sms_sent_on timestamp with time zone,
    email_sent_on timestamp with time zone
);


ALTER TABLE public.copying_request_verify OWNER TO postgres;

--
-- Name: copying_request_verify_documents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.copying_request_verify_documents (
    id bigint NOT NULL,
    order_type bigint NOT NULL,
    order_date timestamp with time zone,
    copying_order_issuing_application_id bigint NOT NULL,
    number_of_copies smallint DEFAULT '1'::smallint,
    number_of_pages_in_pdf bigint DEFAULT '1'::bigint,
    path text,
    from_page bigint,
    to_page bigint,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    order_type_remark character varying(200),
    request_status character varying(45) DEFAULT 'P'::character varying NOT NULL,
    updated_by bigint,
    updated_on timestamp with time zone,
    reject_cause character varying(100),
    sms_sent_on timestamp with time zone,
    email_sent_on timestamp with time zone,
    current_section bigint DEFAULT '10'::bigint NOT NULL,
    fee_clc_for_certification_no_doc bigint,
    fee_clc_for_certification_pages bigint,
    fee_clc_for_uncertification_no_doc bigint,
    fee_clc_for_uncertification_pages bigint,
    fee_clc_creaded_by bigint,
    fee_clc_created_on timestamp with time zone,
    fee_clc_created_ip character varying(20),
    fee_clc_updated_by bigint,
    fee_clc_updated_on timestamp with time zone,
    fee_clc_updated_ip character varying(20)
);


ALTER TABLE public.copying_request_verify_documents OWNER TO postgres;

--
-- Name: copying_request_verify_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.copying_request_verify_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.copying_request_verify_documents_id_seq OWNER TO postgres;

--
-- Name: copying_request_verify_documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.copying_request_verify_documents_id_seq OWNED BY public.copying_request_verify_documents.id;


--
-- Name: copying_request_verify_documents_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.copying_request_verify_documents_log (
    id bigint NOT NULL,
    copying_request_verify_documents_id bigint NOT NULL,
    order_type bigint NOT NULL,
    order_date timestamp with time zone,
    copying_order_issuing_application_id bigint NOT NULL,
    number_of_copies smallint DEFAULT '1'::smallint,
    number_of_pages_in_pdf bigint DEFAULT '1'::bigint,
    path text,
    from_page bigint,
    to_page bigint,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    order_type_remark character varying(200),
    request_status character varying(45) DEFAULT 'P'::character varying NOT NULL,
    updated_by bigint,
    updated_on timestamp with time zone,
    reject_cause character varying(100),
    sms_sent_on timestamp with time zone,
    email_sent_on timestamp with time zone,
    current_section bigint DEFAULT '10'::bigint NOT NULL,
    fee_clc_for_certification_no_doc bigint,
    fee_clc_for_certification_pages bigint,
    fee_clc_for_uncertification_no_doc bigint,
    fee_clc_for_uncertification_pages bigint,
    fee_clc_creaded_by bigint,
    fee_clc_created_on timestamp with time zone,
    fee_clc_created_ip character varying(20),
    fee_clc_updated_by bigint,
    fee_clc_updated_on timestamp with time zone,
    fee_clc_updated_ip character varying(20),
    creaded_by bigint NOT NULL,
    created_on timestamp with time zone,
    created_ip character varying(20) NOT NULL
);


ALTER TABLE public.copying_request_verify_documents_log OWNER TO postgres;

--
-- Name: copying_request_verify_documents_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.copying_request_verify_documents_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.copying_request_verify_documents_log_id_seq OWNER TO postgres;

--
-- Name: copying_request_verify_documents_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.copying_request_verify_documents_log_id_seq OWNED BY public.copying_request_verify_documents_log.id;


--
-- Name: copying_request_verify_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.copying_request_verify_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.copying_request_verify_id_seq OWNER TO postgres;

--
-- Name: copying_request_verify_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.copying_request_verify_id_seq OWNED BY public.copying_request_verify.id;


--
-- Name: copying_trap; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.copying_trap (
    id bigint NOT NULL,
    copying_application_id bigint,
    field character varying(45),
    previous_value character varying(45),
    new_value character varying(45),
    updated_by bigint,
    updated_on timestamp with time zone
);


ALTER TABLE public.copying_trap OWNER TO postgres;

--
-- Name: copying_trap_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.copying_trap_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.copying_trap_id_seq OWNER TO postgres;

--
-- Name: copying_trap_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.copying_trap_id_seq OWNED BY public.copying_trap.id;


--
-- Name: coram; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.coram (
    diary_no bigint,
    board_type character(1),
    jud bigint,
    res_id bigint,
    from_dt date,
    to_dt date,
    usercode bigint,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar,
    del_reason character varying(50)
);


ALTER TABLE public.coram OWNER TO postgres;

--
-- Name: coram_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.coram_a (
    diary_no bigint,
    board_type character(1),
    jud bigint,
    res_id bigint,
    from_dt date,
    to_dt date,
    usercode bigint,
    ent_dt timestamp with time zone,
    display character(1),
    del_reason character varying(50)
);


ALTER TABLE public.coram_a OWNER TO dev;

--
-- Name: court_ip_06012022; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.court_ip_06012022 (
    sno smallint DEFAULT '0'::smallint NOT NULL,
    court_no smallint NOT NULL,
    ip_address character varying(20) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE public.court_ip_06012022 OWNER TO postgres;

--
-- Name: craent; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.craent (
    fil_no character varying(14) NOT NULL,
    sentence bigint,
    status character(1),
    ugone_yr bigint,
    ugone_mon bigint,
    ugone_day bigint,
    ucode bigint NOT NULL,
    entdt timestamp with time zone,
    upd_da bigint NOT NULL,
    sentence_mth bigint NOT NULL,
    act_fine bigint NOT NULL,
    lower_court_id bigint NOT NULL,
    id bigint NOT NULL,
    from_date date,
    to_date date,
    accused_id bigint NOT NULL
);


ALTER TABLE public.craent OWNER TO postgres;

--
-- Name: craent_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.craent_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.craent_id_seq OWNER TO postgres;

--
-- Name: craent_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.craent_id_seq OWNED BY public.craent.id;


--
-- Name: criminal_matters_category_new; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.criminal_matters_category_new (
    diary_no bigint NOT NULL,
    old_category bigint,
    new_category bigint
);


ALTER TABLE public.criminal_matters_category_new OWNER TO postgres;

--
-- Name: dashboard_data; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dashboard_data (
    id bigint NOT NULL,
    flag character varying(72) NOT NULL,
    da_code bigint DEFAULT '0'::bigint NOT NULL,
    counted_data bigint DEFAULT '0'::bigint NOT NULL,
    list_date date,
    with_connected character(1) DEFAULT 'Y'::bpchar NOT NULL,
    is_active character(1) DEFAULT 'Y'::bpchar NOT NULL,
    ason timestamp with time zone,
    roster_id bigint DEFAULT '0'::bigint NOT NULL
);


ALTER TABLE public.dashboard_data OWNER TO postgres;

--
-- Name: dashboard_data_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.dashboard_data_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.dashboard_data_id_seq OWNER TO postgres;

--
-- Name: dashboard_data_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.dashboard_data_id_seq OWNED BY public.dashboard_data.id;


--
-- Name: dashboard_data_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dashboard_data_log (
    id bigint DEFAULT '0'::bigint NOT NULL,
    flag character varying(72) NOT NULL,
    da_code bigint DEFAULT '0'::bigint NOT NULL,
    counted_data bigint DEFAULT '0'::bigint NOT NULL,
    list_date date,
    with_connected character(1) DEFAULT 'Y'::bpchar NOT NULL,
    is_active character(1) DEFAULT 'Y'::bpchar NOT NULL,
    ason timestamp with time zone,
    roster_id bigint DEFAULT '0'::bigint NOT NULL
);


ALTER TABLE public.dashboard_data_log OWNER TO postgres;

--
-- Name: data_tentative_dates; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.data_tentative_dates (
    id bigint NOT NULL,
    judge_id bigint NOT NULL,
    next_dt date,
    non_fix_date_count bigint DEFAULT '0'::bigint NOT NULL,
    fix_date_count bigint DEFAULT '0'::bigint NOT NULL,
    is_nmd character(1) DEFAULT 'N'::bpchar NOT NULL,
    entry_date timestamp with time zone,
    diary_no text
);


ALTER TABLE public.data_tentative_dates OWNER TO postgres;

--
-- Name: data_tentative_dates_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.data_tentative_dates_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.data_tentative_dates_id_seq OWNER TO postgres;

--
-- Name: data_tentative_dates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.data_tentative_dates_id_seq OWNED BY public.data_tentative_dates.id;


--
-- Name: data_tentative_dates_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.data_tentative_dates_log (
    id bigint NOT NULL,
    judge_id bigint NOT NULL,
    next_dt date,
    non_fix_date_count bigint DEFAULT '0'::bigint NOT NULL,
    fix_date_count bigint DEFAULT '0'::bigint NOT NULL,
    is_nmd character(1) DEFAULT 'N'::bpchar NOT NULL,
    entry_date timestamp with time zone,
    diary_no text
);


ALTER TABLE public.data_tentative_dates_log OWNER TO postgres;

--
-- Name: defect_case_list_26032019; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.defect_case_list_26032019 (
    id bigint NOT NULL,
    diary_no bigint,
    case_title character varying(200),
    next_dt date,
    ent_dt timestamp with time zone
);


ALTER TABLE public.defect_case_list_26032019 OWNER TO postgres;

--
-- Name: defect_case_list_26032019_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.defect_case_list_26032019_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.defect_case_list_26032019_id_seq OWNER TO postgres;

--
-- Name: defect_case_list_26032019_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.defect_case_list_26032019_id_seq OWNED BY public.defect_case_list_26032019.id;


--
-- Name: defective_chamber_listing; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.defective_chamber_listing (
    id bigint NOT NULL,
    listing_date date,
    display character(1) DEFAULT 'Y'::bpchar,
    ent_by bigint NOT NULL,
    ent_on timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.defective_chamber_listing OWNER TO postgres;

--
-- Name: defective_chamber_listing_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.defective_chamber_listing_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.defective_chamber_listing_id_seq OWNER TO postgres;

--
-- Name: defective_chamber_listing_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.defective_chamber_listing_id_seq OWNED BY public.defective_chamber_listing.id;


--
-- Name: defects_notified_mails; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.defects_notified_mails (
    id bigint NOT NULL,
    to_sender character varying(500),
    subject character varying(200),
    display character varying(45),
    usercode bigint,
    created_on character varying(45)
);


ALTER TABLE public.defects_notified_mails OWNER TO postgres;

--
-- Name: defects_notified_mails_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.defects_notified_mails_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.defects_notified_mails_id_seq OWNER TO postgres;

--
-- Name: defects_notified_mails_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.defects_notified_mails_id_seq OWNED BY public.defects_notified_mails.id;


--
-- Name: defects_verification; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.defects_verification (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    verification_status character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    verification_date timestamp with time zone,
    user_id bigint,
    remarks character varying(200),
    user_ip character varying(100)
);


ALTER TABLE public.defects_verification OWNER TO postgres;

--
-- Name: defects_verification_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.defects_verification_a (
    id bigint,
    diary_no bigint,
    verification_status character varying(1),
    verification_date timestamp with time zone,
    user_id bigint,
    remarks character varying(200),
    user_ip character varying(100)
);


ALTER TABLE public.defects_verification_a OWNER TO dev;

--
-- Name: defects_verification_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.defects_verification_history (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    verification_status character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    verification_date timestamp with time zone,
    user_id bigint,
    remarks character varying(200),
    user_ip character varying(100),
    deleted_on timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    deleted_remarks character varying(100)
);


ALTER TABLE public.defects_verification_history OWNER TO postgres;

--
-- Name: defects_verification_history_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.defects_verification_history_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.defects_verification_history_id_seq OWNER TO postgres;

--
-- Name: defects_verification_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.defects_verification_history_id_seq OWNED BY public.defects_verification_history.id;


--
-- Name: defects_verification_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.defects_verification_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.defects_verification_id_seq OWNER TO postgres;

--
-- Name: defects_verification_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.defects_verification_id_seq OWNED BY public.defects_verification.id;


--
-- Name: diary_copy_set; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.diary_copy_set (
    id bigint NOT NULL,
    diary_no bigint,
    copy_set character(1) NOT NULL,
    create_modify timestamp without time zone,
    updated_by bigint,
    updated_by_ip text
);


ALTER TABLE public.diary_copy_set OWNER TO postgres;

--
-- Name: diary_copy_set_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.diary_copy_set_a (
    id bigint,
    diary_no bigint,
    copy_set character(1)
);


ALTER TABLE public.diary_copy_set_a OWNER TO dev;

--
-- Name: diary_copy_set_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.diary_copy_set_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.diary_copy_set_id_seq OWNER TO postgres;

--
-- Name: diary_copy_set_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.diary_copy_set_id_seq OWNED BY public.diary_copy_set.id;


--
-- Name: diary_movement; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.diary_movement (
    id bigint NOT NULL,
    diary_copy_set bigint NOT NULL,
    disp_by bigint NOT NULL,
    disp_to bigint NOT NULL,
    disp_dt timestamp with time zone,
    rece_by bigint NOT NULL,
    rece_dt timestamp with time zone,
    c_l character(1) NOT NULL,
    remark character varying(200) NOT NULL,
    flag boolean NOT NULL
);


ALTER TABLE public.diary_movement OWNER TO postgres;

--
-- Name: diary_movement_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.diary_movement_history (
    id bigint NOT NULL,
    diary_copy_set bigint NOT NULL,
    disp_by bigint NOT NULL,
    disp_to bigint NOT NULL,
    disp_dt timestamp with time zone,
    rece_by bigint NOT NULL,
    rece_dt timestamp with time zone,
    c_l character(1) NOT NULL,
    remark character varying(200) NOT NULL,
    flag boolean NOT NULL,
    ent_dt timestamp with time zone
);


ALTER TABLE public.diary_movement_history OWNER TO postgres;

--
-- Name: diary_movement_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.diary_movement_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.diary_movement_id_seq OWNER TO postgres;

--
-- Name: diary_movement_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.diary_movement_id_seq OWNED BY public.diary_movement.id;


--
-- Name: digital_certification_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.digital_certification_details (
    id bigint NOT NULL,
    certificate_number bigint,
    certificate_year bigint,
    faster_cases_id bigint,
    faster_shared_document_details_id bigint,
    created_at timestamp with time zone,
    created_by bigint,
    is_deleted smallint DEFAULT '0'::smallint
);


ALTER TABLE public.digital_certification_details OWNER TO postgres;

--
-- Name: digital_certification_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.digital_certification_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.digital_certification_details_id_seq OWNER TO postgres;

--
-- Name: digital_certification_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.digital_certification_details_id_seq OWNED BY public.digital_certification_details.id;


--
-- Name: dispose; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dispose (
    diary_no bigint NOT NULL,
    fil_no character varying(16),
    month bigint DEFAULT 0 NOT NULL,
    dispjud bigint DEFAULT 0 NOT NULL,
    year bigint DEFAULT 0 NOT NULL,
    ord_dt date,
    disp_dt date,
    disp_dt_old date,
    disp_type bigint,
    bench character(1),
    jud_id character varying(100) NOT NULL,
    camnt bigint DEFAULT '0'::bigint,
    crtstat character(1),
    usercode bigint,
    ent_dt timestamp with time zone,
    jorder text,
    rj_dt date,
    disp_type_all character varying(30) NOT NULL,
    create_modify timestamp with time zone
);


ALTER TABLE public.dispose OWNER TO postgres;

--
-- Name: dispose_delete; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dispose_delete (
    diary_no bigint NOT NULL,
    fil_no character varying(16) DEFAULT ''::character varying NOT NULL,
    month bigint DEFAULT 0 NOT NULL,
    dispjud bigint DEFAULT 0 NOT NULL,
    year bigint DEFAULT 0 NOT NULL,
    ord_dt date,
    disp_dt date,
    disp_type bigint,
    bench character(1),
    jud_id character varying(100) DEFAULT '0'::character varying NOT NULL,
    camnt bigint DEFAULT '0'::bigint,
    crtstat character(1),
    usercode bigint,
    ent_dt timestamp with time zone,
    jorder text,
    rj_dt date,
    disp_type_all character varying(30) NOT NULL,
    entered_on timestamp with time zone,
    dispose_updated_by bigint NOT NULL,
    is_active character varying(2) DEFAULT 't'::character varying
);


ALTER TABLE public.dispose_delete OWNER TO postgres;

--
-- Name: docdetails; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.docdetails (
    diary_no bigint NOT NULL,
    doccode bigint DEFAULT 0 NOT NULL,
    doccode1 bigint DEFAULT 0 NOT NULL,
    docnum bigint DEFAULT 0,
    docyear bigint DEFAULT 0,
    filedby character varying(50),
    docfee bigint DEFAULT '0'::bigint,
    other1 character varying(100),
    iastat character varying(10) DEFAULT 'P'::bpchar,
    forresp character varying(50),
    feemode character(1) DEFAULT 'O'::bpchar,
    fee1 bigint DEFAULT 0,
    fee2 bigint DEFAULT 0,
    usercode bigint DEFAULT 0,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar,
    remark character varying(150),
    lst_mdf timestamp with time zone,
    lst_user bigint,
    j1 bigint,
    j2 bigint,
    j3 bigint,
    party character varying(100),
    advocate_id bigint,
    verified character(1),
    verified_by bigint,
    verified_on timestamp with time zone,
    sc_ia_sta_code bigint,
    sc_ref_code_id bigint,
    sc_application_no character varying(50),
    no_of_copy bigint,
    sc_old_doc_code bigint,
    docd_id bigint NOT NULL,
    verified_remarks text,
    dispose_date date,
    last_modified_by bigint,
    disposal_remark character varying(100),
    is_efiled character(1)
);


ALTER TABLE public.docdetails OWNER TO postgres;

--
-- Name: docdetails_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.docdetails_a (
    diary_no bigint,
    doccode bigint,
    doccode1 bigint,
    docnum bigint,
    docyear bigint,
    filedby character varying(50),
    docfee bigint,
    other1 character varying(100),
    iastat character(1),
    forresp character varying(50),
    feemode character(1),
    fee1 bigint,
    fee2 bigint,
    usercode bigint,
    ent_dt timestamp with time zone,
    display character(1),
    remark character varying(150),
    lst_mdf timestamp with time zone,
    lst_user bigint,
    j1 bigint,
    j2 bigint,
    j3 bigint,
    party character varying(100),
    advocate_id bigint,
    verified character(1),
    verified_by bigint,
    verified_on timestamp with time zone,
    sc_ia_sta_code bigint,
    sc_ref_code_id bigint,
    sc_application_no character varying(50),
    no_of_copy bigint,
    sc_old_doc_code bigint,
    docd_id bigint,
    verified_remarks text,
    dispose_date date,
    last_modified_by bigint,
    disposal_remark character varying(100),
    is_efiled character(1)
);


ALTER TABLE public.docdetails_a OWNER TO dev;

--
-- Name: docdetails_docd_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.docdetails_docd_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.docdetails_docd_id_seq OWNER TO postgres;

--
-- Name: docdetails_docd_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.docdetails_docd_id_seq OWNED BY public.docdetails.docd_id;


--
-- Name: docdetails_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.docdetails_history (
    diary_no bigint NOT NULL,
    doccode bigint DEFAULT 0 NOT NULL,
    doccode1 bigint DEFAULT 0 NOT NULL,
    docnum bigint DEFAULT 0,
    docyear bigint DEFAULT 0,
    filedby character varying(50),
    docfee bigint DEFAULT '0'::bigint,
    other1 character varying(100),
    iastat character(1) DEFAULT 'P'::bpchar NOT NULL,
    forresp character varying(50),
    feemode character(1) DEFAULT 'O'::bpchar,
    fee1 bigint DEFAULT 0 NOT NULL,
    fee2 bigint DEFAULT 0 NOT NULL,
    usercode bigint DEFAULT 0 NOT NULL,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    remark character varying(150),
    lst_mdf timestamp with time zone,
    lst_user bigint NOT NULL,
    j1 bigint NOT NULL,
    j2 bigint NOT NULL,
    j3 bigint NOT NULL,
    party character varying(100) NOT NULL,
    advocate_id bigint NOT NULL,
    verified character(1) NOT NULL,
    verified_by bigint NOT NULL,
    verified_on timestamp with time zone,
    sc_ia_sta_code bigint NOT NULL,
    sc_ref_code_id bigint NOT NULL,
    sc_application_no character varying(50) NOT NULL,
    no_of_copy bigint NOT NULL,
    sc_old_doc_code bigint,
    docd_id numeric DEFAULT '0'::numeric NOT NULL,
    verified_remarks text NOT NULL,
    dispose_date date,
    last_modified_by bigint,
    disposal_remark character varying(45),
    is_efiled character(1),
    update_by bigint,
    update_on timestamp with time zone
);


ALTER TABLE public.docdetails_history OWNER TO postgres;

--
-- Name: docdetails_remark; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.docdetails_remark (
    remark_data character varying(200)
);


ALTER TABLE public.docdetails_remark OWNER TO postgres;

--
-- Name: docdetails_uploaded_documents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.docdetails_uploaded_documents (
    id bigint NOT NULL,
    ref_docdetails_docd_id bigint,
    pdf_path character varying(500) NOT NULL,
    is_verified character varying(5),
    uploaded_by bigint,
    uploaded_on timestamp with time zone,
    uploaded_by_ip character varying(45),
    verified_by bigint,
    verified_on timestamp with time zone,
    verified_by_ip character varying(45),
    actual_refiling_date date,
    defects character varying(200),
    is_downloaded character varying(1) DEFAULT 'f'::character varying,
    is_inserted_into_ingestion character varying(1) DEFAULT 'f'::character varying,
    last_downloaded_on timestamp with time zone,
    inserted_into_ingestion_on timestamp with time zone,
    diary_no bigint,
    no_of_pages bigint
);


ALTER TABLE public.docdetails_uploaded_documents OWNER TO postgres;

--
-- Name: docdetails_uploaded_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.docdetails_uploaded_documents_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.docdetails_uploaded_documents_id_seq OWNER TO postgres;

--
-- Name: docdetails_uploaded_documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.docdetails_uploaded_documents_id_seq OWNED BY public.docdetails_uploaded_documents.id;


--
-- Name: docdetails_uploaded_documents_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.docdetails_uploaded_documents_log (
    id bigint NOT NULL,
    ref_docdetails_uploaded_documents_id bigint,
    ref_docdetails_docd_id bigint,
    pdf_path character varying(500) NOT NULL,
    is_verified character varying(5),
    uploaded_by bigint,
    uploaded_on timestamp with time zone,
    uploaded_by_ip character varying(45),
    verified_by bigint,
    verified_on timestamp with time zone,
    verified_by_ip character varying(45),
    actual_refiling_date date,
    defects character varying(200),
    modified_by bigint,
    modified_on timestamp with time zone,
    modified_by_ip character varying(45),
    reason character varying(2),
    is_downloaded character varying(1) DEFAULT 'f'::character varying,
    is_inserted_into_ingestion character varying(1) DEFAULT 'f'::character varying,
    last_downloaded_on timestamp with time zone,
    inserted_into_ingestion_on timestamp with time zone,
    diary_no bigint,
    no_of_pages bigint
);


ALTER TABLE public.docdetails_uploaded_documents_log OWNER TO postgres;

--
-- Name: docdetails_uploaded_documents_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.docdetails_uploaded_documents_log_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.docdetails_uploaded_documents_log_id_seq OWNER TO postgres;

--
-- Name: docdetails_uploaded_documents_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.docdetails_uploaded_documents_log_id_seq OWNED BY public.docdetails_uploaded_documents_log.id;


--
-- Name: draft_list; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.draft_list (
    diary_no bigint NOT NULL,
    next_dt_old date NOT NULL,
    conn_key bigint NOT NULL,
    list_type bigint NOT NULL,
    board_type character(1) NOT NULL,
    usercode bigint NOT NULL,
    ent_time timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE public.draft_list OWNER TO postgres;

--
-- Name: drop_note; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.drop_note (
    id bigint NOT NULL,
    cl_date date,
    clno bigint NOT NULL,
    diary_no bigint NOT NULL,
    roster_id bigint NOT NULL,
    nrs character varying(75),
    usercode bigint NOT NULL,
    ent_dt timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    mf character(1),
    update_time timestamp with time zone,
    update_user character varying(5) NOT NULL,
    so_user character varying(5) NOT NULL,
    so_time timestamp with time zone,
    part bigint NOT NULL,
    reason_id bigint DEFAULT '0'::bigint NOT NULL,
    reason_type_id bigint NOT NULL
);


ALTER TABLE public.drop_note OWNER TO postgres;

--
-- Name: drop_note_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.drop_note_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.drop_note_id_seq OWNER TO postgres;

--
-- Name: drop_note_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.drop_note_id_seq OWNED BY public.drop_note.id;


--
-- Name: duplicate_reg_no; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.duplicate_reg_no (
    diary_no2 bigint DEFAULT '0'::bigint NOT NULL,
    active_fil_no2 character varying(16) NOT NULL,
    ct2 bigint DEFAULT 0 NOT NULL,
    from_no2 bigint DEFAULT 0 NOT NULL,
    to_no2 bigint DEFAULT 0 NOT NULL,
    active_reg_year2 bigint NOT NULL,
    no2 bigint NOT NULL,
    active_fil_dt2 timestamp with time zone,
    "count( * )" bigint DEFAULT '0'::bigint NOT NULL,
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    active_fil_no character varying(16) NOT NULL,
    ct bigint DEFAULT 0 NOT NULL,
    from_no bigint DEFAULT 0 NOT NULL,
    to_no bigint DEFAULT 0 NOT NULL,
    active_reg_year bigint NOT NULL,
    no bigint NOT NULL,
    active_fil_dt timestamp with time zone
);


ALTER TABLE public.duplicate_reg_no OWNER TO postgres;

--
-- Name: ec_forward_letter_images; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_forward_letter_images (
    id bigint NOT NULL,
    file_display_name character varying(20),
    file_path character varying(40),
    file_name character varying(30),
    upload_time timestamp with time zone,
    upload_by character varying(20),
    is_deleted character(1) DEFAULT 'f'::bpchar
);


ALTER TABLE public.ec_forward_letter_images OWNER TO postgres;

--
-- Name: ec_forward_letter_images_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ec_forward_letter_images_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ec_forward_letter_images_id_seq OWNER TO postgres;

--
-- Name: ec_forward_letter_images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ec_forward_letter_images_id_seq OWNED BY public.ec_forward_letter_images.id;


--
-- Name: ec_forward_letter_postal_transactions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_forward_letter_postal_transactions (
    transactions_id bigint,
    image_id bigint
);


ALTER TABLE public.ec_forward_letter_postal_transactions OWNER TO postgres;

--
-- Name: ec_keyword; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_keyword (
    id bigint NOT NULL,
    diary_no bigint,
    keyword_id bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    ent_dt timestamp with time zone,
    updated_from_ip character varying(15),
    updatedfrommodule character varying(45),
    "user" bigint
);


ALTER TABLE public.ec_keyword OWNER TO postgres;

--
-- Name: ec_keyword_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ec_keyword_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ec_keyword_id_seq OWNER TO postgres;

--
-- Name: ec_keyword_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ec_keyword_id_seq OWNED BY public.ec_keyword.id;


--
-- Name: ec_pil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_pil (
    id bigint NOT NULL,
    ref_language_id bigint,
    received_from text,
    address text,
    ref_state_id bigint,
    ref_city_id bigint,
    received_on timestamp with time zone,
    petition_date timestamp with time zone,
    subject text,
    ref_pil_category_id bigint,
    diary_year bigint,
    is_deleted character varying(5) DEFAULT 'f'::character varying,
    adm_updated_by bigint,
    updated_on timestamp with time zone,
    group_file_number bigint,
    action_taken character varying(50),
    lodgment_date timestamp with time zone,
    written_to text,
    written_for text,
    return_date timestamp with time zone,
    sent_to text,
    sent_on timestamp with time zone,
    remedy_text text,
    report_received character varying(5),
    report_received_date timestamp with time zone,
    destroy_on timestamp with time zone,
    in_record_on timestamp with time zone,
    remarks text,
    ec_case_id bigint,
    letter_date timestamp with time zone,
    action_taken_on timestamp with time zone,
    transfered_on timestamp with time zone,
    transfered_to text,
    ir_received_on timestamp with time zone,
    ir_received_from text,
    submitted_note_on timestamp with time zone,
    submitted_note_to text,
    judgment_on_submitted_note text,
    comp_order text,
    weeded_on timestamp with time zone,
    diary_number bigint,
    email character varying(200),
    vernacular_language character varying(5),
    address_to text,
    returned_to_sender_remarks text,
    written_on timestamp with time zone,
    lodged_action_reason text,
    mobile text,
    other_text text,
    middle_name text,
    last_name text,
    registration_date date,
    ref_action_taken_id bigint,
    request_summary text,
    other_action_taken_on timestamp with time zone
);


ALTER TABLE public.ec_pil OWNER TO postgres;

--
-- Name: ec_pil_group_file; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_pil_group_file (
    id bigint NOT NULL,
    group_file_number text NOT NULL,
    is_deleted character varying(5) DEFAULT 'f'::character varying NOT NULL,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone
);


ALTER TABLE public.ec_pil_group_file OWNER TO postgres;

--
-- Name: ec_pil_group_file_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ec_pil_group_file_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ec_pil_group_file_id_seq OWNER TO postgres;

--
-- Name: ec_pil_group_file_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ec_pil_group_file_id_seq OWNED BY public.ec_pil_group_file.id;


--
-- Name: ec_pil_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ec_pil_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ec_pil_id_seq OWNER TO postgres;

--
-- Name: ec_pil_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ec_pil_id_seq OWNED BY public.ec_pil.id;


--
-- Name: ec_pil_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_pil_log (
    id bigint NOT NULL,
    ref_language_id bigint,
    received_from text,
    address text,
    ref_state_id bigint,
    ref_city_id bigint,
    received_on timestamp with time zone,
    petition_date timestamp with time zone,
    subject character varying(500),
    ref_pil_category_id bigint,
    diary_year bigint,
    is_deleted smallint,
    adm_updated_by bigint,
    updated_on timestamp with time zone,
    group_file_number bigint,
    action_taken character varying(50),
    lodgment_date timestamp with time zone,
    written_to character varying(300),
    written_for text,
    return_date timestamp with time zone,
    sent_to character varying(300),
    sent_on timestamp with time zone,
    remedy_text character varying(1024),
    report_received smallint,
    report_received_date timestamp with time zone,
    destroy_on timestamp with time zone,
    in_record_on timestamp with time zone,
    remarks text,
    ec_case_id bigint,
    letter_date timestamp with time zone,
    action_taken_on timestamp with time zone,
    transfered_on timestamp with time zone,
    transfered_to character varying(512),
    ir_received_on timestamp with time zone,
    ir_received_from character varying(512),
    submitted_note_on timestamp with time zone,
    submitted_note_to character varying(512),
    judgment_on_submitted_note character varying(1024),
    comp_order character varying(1024),
    weeded_on timestamp with time zone,
    diary_number character varying(10),
    email character varying(200),
    vernacular_language smallint,
    address_to character varying(1000),
    returned_to_sender_remarks character varying(1024),
    written_on timestamp with time zone,
    lodged_action_reason text,
    mobile text,
    other_text character varying(1024),
    middle_name text,
    last_name text,
    registration_date date,
    ref_action_taken_id bigint,
    request_summary text,
    other_action_taken_on timestamp with time zone
);


ALTER TABLE public.ec_pil_log OWNER TO postgres;

--
-- Name: ec_postal_dispatch; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_postal_dispatch (
    id bigint NOT NULL,
    is_with_process_id smallint DEFAULT '0'::smallint,
    is_case smallint DEFAULT '0'::smallint,
    tw_notice_id bigint,
    diary_no bigint,
    reference_number character varying(50),
    tw_tal_del_id bigint,
    process_id bigint,
    process_id_year bigint,
    send_to_name character varying(100),
    send_to_address character varying(100),
    tal_state bigint,
    tal_district bigint,
    pincode bigint,
    ref_postal_type_id bigint,
    dispatched_by bigint,
    serial_number bigint,
    dispatched_on timestamp with time zone,
    postal_charges double precision,
    weight character varying(45),
    waybill_number character varying(200),
    is_acknowledgeable smallint DEFAULT '0'::smallint,
    is_acknowledged smallint DEFAULT '0'::smallint,
    ref_letter_status_id bigint,
    usersection_id bigint,
    remarks character varying(100),
    serve_stage bigint,
    tw_serve_id bigint,
    serve_remarks character varying(100),
    usercode bigint,
    updated_on timestamp with time zone
);


ALTER TABLE public.ec_postal_dispatch OWNER TO postgres;

--
-- Name: COLUMN ec_postal_dispatch.is_acknowledgeable; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.ec_postal_dispatch.is_acknowledgeable IS 'Tracking Number';


--
-- Name: ec_postal_dispatch_connected_letters; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_postal_dispatch_connected_letters (
    id bigint NOT NULL,
    ec_postal_dispatch_id bigint,
    ec_postal_dispatch_id_main bigint,
    usercode bigint,
    updated_on timestamp with time zone,
    is_deleted smallint DEFAULT '0'::smallint
);


ALTER TABLE public.ec_postal_dispatch_connected_letters OWNER TO postgres;

--
-- Name: ec_postal_dispatch_connected_letters_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_postal_dispatch_connected_letters_history (
    id bigint NOT NULL,
    ec_postal_dispatch_id bigint,
    ec_postal_dispatch_id_main bigint,
    usercode bigint,
    updated_on timestamp with time zone,
    is_deleted smallint DEFAULT '0'::smallint
);


ALTER TABLE public.ec_postal_dispatch_connected_letters_history OWNER TO postgres;

--
-- Name: ec_postal_dispatch_connected_letters_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ec_postal_dispatch_connected_letters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ec_postal_dispatch_connected_letters_id_seq OWNER TO postgres;

--
-- Name: ec_postal_dispatch_connected_letters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ec_postal_dispatch_connected_letters_id_seq OWNED BY public.ec_postal_dispatch_connected_letters.id;


--
-- Name: ec_postal_dispatch_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ec_postal_dispatch_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ec_postal_dispatch_id_seq OWNER TO postgres;

--
-- Name: ec_postal_dispatch_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ec_postal_dispatch_id_seq OWNED BY public.ec_postal_dispatch.id;


--
-- Name: ec_postal_dispatch_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_postal_dispatch_log (
    id bigint NOT NULL,
    is_with_process_id smallint DEFAULT '0'::smallint,
    is_case smallint DEFAULT '0'::smallint,
    tw_notice_id bigint,
    diary_no bigint,
    reference_number character varying(50),
    tw_tal_del_id bigint,
    process_id bigint,
    process_id_year bigint,
    send_to_name character varying(100),
    send_to_address character varying(100),
    tal_state bigint,
    tal_district bigint,
    pincode bigint,
    ref_postal_type_id bigint,
    dispatched_by bigint,
    serial_number bigint,
    dispatched_on timestamp with time zone,
    postal_charges double precision,
    weight character varying(45),
    waybill_number character varying(200),
    is_acknowledgeable smallint DEFAULT '0'::smallint,
    is_acknowledged smallint DEFAULT '0'::smallint,
    ref_letter_status_id bigint,
    usersection_id bigint,
    remarks character varying(100),
    serve_stage bigint,
    tw_serve_id bigint,
    serve_remarks character varying(100),
    usercode bigint,
    updated_on timestamp with time zone
);


ALTER TABLE public.ec_postal_dispatch_log OWNER TO postgres;

--
-- Name: COLUMN ec_postal_dispatch_log.is_acknowledgeable; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.ec_postal_dispatch_log.is_acknowledgeable IS 'Tracking Number';


--
-- Name: ec_postal_dispatch_transactions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_postal_dispatch_transactions (
    id bigint NOT NULL,
    ec_postal_dispatch_id bigint,
    ref_letter_status_id bigint,
    remarks character varying(100),
    usercode bigint,
    updated_on timestamp with time zone
);


ALTER TABLE public.ec_postal_dispatch_transactions OWNER TO postgres;

--
-- Name: ec_postal_dispatch_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ec_postal_dispatch_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ec_postal_dispatch_transactions_id_seq OWNER TO postgres;

--
-- Name: ec_postal_dispatch_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ec_postal_dispatch_transactions_id_seq OWNED BY public.ec_postal_dispatch_transactions.id;


--
-- Name: ec_postal_received; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_postal_received (
    id bigint NOT NULL,
    ref_postal_type_id bigint,
    is_openable character varying(5) NOT NULL,
    postal_no character varying(100),
    postal_date date,
    letter_no character varying(100),
    letter_year bigint,
    letter_date date,
    subject character varying(100),
    is_original_record character varying(5) NOT NULL,
    sender_name character varying(75),
    address text,
    ref_city_id bigint,
    ref_state_id bigint,
    pin_code bigint,
    diary_no bigint,
    diary_year bigint,
    updated_on timestamp(6) with time zone,
    adm_updated_by bigint NOT NULL,
    is_deleted character varying(1) DEFAULT 'f'::character varying NOT NULL,
    postal_addressee character varying(75),
    ec_case_id bigint,
    pil_diary_number character varying(50),
    remarks text,
    received_on timestamp with time zone,
    is_ad_card smallint DEFAULT '0'::smallint,
    ec_postal_dispatch_id bigint
);


ALTER TABLE public.ec_postal_received OWNER TO postgres;

--
-- Name: ec_postal_received_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ec_postal_received_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ec_postal_received_id_seq OWNER TO postgres;

--
-- Name: ec_postal_received_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ec_postal_received_id_seq OWNED BY public.ec_postal_received.id;


--
-- Name: ec_postal_received_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_postal_received_log (
    id bigint NOT NULL,
    ref_postal_type_id bigint,
    is_openable character varying(5) NOT NULL,
    postal_no character varying(100),
    postal_date date,
    letter_no character varying(100),
    letter_year bigint,
    letter_date date,
    subject character varying(100),
    is_original_record character varying(5) NOT NULL,
    sender_name character varying(75),
    address text,
    ref_city_id bigint,
    ref_state_id bigint,
    pin_code bigint,
    diary_no bigint,
    diary_year bigint,
    updated_on timestamp(6) with time zone,
    adm_updated_by bigint NOT NULL,
    is_deleted character varying(1) DEFAULT 'f'::character varying NOT NULL,
    postal_addressee character varying(75),
    ec_case_id bigint,
    pil_diary_number character varying(50),
    remarks text,
    received_on timestamp with time zone,
    is_ad_card smallint DEFAULT '0'::smallint,
    ec_postal_dispatch_id bigint
);


ALTER TABLE public.ec_postal_received_log OWNER TO postgres;

--
-- Name: ec_postal_transactions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_postal_transactions (
    id bigint NOT NULL,
    ec_postal_received_id bigint,
    ec_postal_user_initiated_letter_id bigint,
    dispatched_to_user_type character varying(1),
    dispatched_to bigint,
    dispatched_by bigint,
    dispatched_on timestamp with time zone,
    action_taken bigint,
    action_taken_on timestamp with time zone,
    action_taken_by bigint,
    return_reason character varying(100),
    last_updated_on timestamp with time zone,
    is_active character(1) DEFAULT 't'::bpchar,
    is_deleted character(1) DEFAULT 'f'::bpchar,
    is_forwarded character(1) DEFAULT 'f'::bpchar,
    letterpriority boolean
);


ALTER TABLE public.ec_postal_transactions OWNER TO postgres;

--
-- Name: ec_postal_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ec_postal_transactions_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ec_postal_transactions_id_seq OWNER TO postgres;

--
-- Name: ec_postal_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ec_postal_transactions_id_seq OWNED BY public.ec_postal_transactions.id;


--
-- Name: ec_postal_user_initiated_letter; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ec_postal_user_initiated_letter (
    id bigint NOT NULL,
    letter_no character varying(100),
    letter_subject character varying(100),
    initiated_on timestamp with time zone,
    initiated_by bigint NOT NULL,
    user_section character varying(45),
    is_deleted character(1) DEFAULT 'f'::bpchar,
    updated_on timestamp with time zone
);


ALTER TABLE public.ec_postal_user_initiated_letter OWNER TO postgres;

--
-- Name: ec_postal_user_initiated_letter_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ec_postal_user_initiated_letter_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ec_postal_user_initiated_letter_id_seq OWNER TO postgres;

--
-- Name: ec_postal_user_initiated_letter_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ec_postal_user_initiated_letter_id_seq OWNED BY public.ec_postal_user_initiated_letter.id;


--
-- Name: efiled_cases; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.efiled_cases (
    id bigint NOT NULL,
    efiling_no character varying(45),
    efiled_type character varying(45),
    diary_no bigint,
    created_at timestamp with time zone,
    created_by bigint,
    display character(1) DEFAULT 'Y'::bpchar,
    create_modify timestamp with time zone,
    updated_on timestamp with time zone,
    updated_by bigint,
    updated_by_ip character varying(100)
);


ALTER TABLE public.efiled_cases OWNER TO postgres;

--
-- Name: efiled_cases_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.efiled_cases_history (
    id bigint DEFAULT '0'::bigint NOT NULL,
    efiling_no character varying(45),
    efiled_type character varying(45),
    diary_no bigint,
    created_at timestamp with time zone,
    created_by bigint,
    display character(1) DEFAULT 'Y'::bpchar,
    deleted_at timestamp with time zone,
    deleted_by character varying(45)
);


ALTER TABLE public.efiled_cases_history OWNER TO postgres;

--
-- Name: efiled_cases_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.efiled_cases_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.efiled_cases_id_seq OWNER TO postgres;

--
-- Name: efiled_cases_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.efiled_cases_id_seq OWNED BY public.efiled_cases.id;


--
-- Name: efiled_cases_transfer_status; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.efiled_cases_transfer_status (
    id bigint NOT NULL,
    diary_no bigint,
    updated_by character varying(45),
    updated_on timestamp with time zone,
    updated_by_ip character varying(45),
    diary_update_by character varying(45),
    diary_update_on timestamp with time zone,
    party_update_by character varying(45),
    party_update_on timestamp with time zone
);


ALTER TABLE public.efiled_cases_transfer_status OWNER TO postgres;

--
-- Name: efiled_cases_transfer_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.efiled_cases_transfer_status_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.efiled_cases_transfer_status_id_seq OWNER TO postgres;

--
-- Name: efiled_cases_transfer_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.efiled_cases_transfer_status_id_seq OWNED BY public.efiled_cases_transfer_status.id;


--
-- Name: efiled_docs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.efiled_docs (
    id bigint NOT NULL,
    efiling_no character varying(45),
    efiled_type character varying(45),
    diary_no bigint,
    doc_id character varying(100),
    docnum bigint,
    docyear bigint,
    created_at timestamp with time zone,
    created_by bigint,
    display character(1) DEFAULT 'Y'::bpchar,
    docd_id bigint
);


ALTER TABLE public.efiled_docs OWNER TO postgres;

--
-- Name: efiled_docs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.efiled_docs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.efiled_docs_id_seq OWNER TO postgres;

--
-- Name: efiled_docs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.efiled_docs_id_seq OWNED BY public.efiled_docs.id;


--
-- Name: efiled_pdfs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.efiled_pdfs (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    diary_year bigint NOT NULL,
    file_name character varying(200) NOT NULL,
    full_path character varying(1000) NOT NULL,
    is_deleted boolean NOT NULL,
    updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    deleted_on timestamp with time zone
);


ALTER TABLE public.efiled_pdfs OWNER TO postgres;

--
-- Name: efiled_pdfs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.efiled_pdfs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.efiled_pdfs_id_seq OWNER TO postgres;

--
-- Name: efiled_pdfs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.efiled_pdfs_id_seq OWNED BY public.efiled_pdfs.id;


--
-- Name: efiling_mails; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.efiling_mails (
    id bigint NOT NULL,
    diaryno character varying(45),
    send_to character varying(45),
    subject character varying(45),
    message character varying(500),
    sent_on character varying(45),
    usercode bigint,
    display character(1)
);


ALTER TABLE public.efiling_mails OWNER TO postgres;

--
-- Name: efiling_mails_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.efiling_mails_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.efiling_mails_id_seq OWNER TO postgres;

--
-- Name: efiling_mails_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.efiling_mails_id_seq OWNED BY public.efiling_mails.id;


--
-- Name: eliminated_cases; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.eliminated_cases (
    diary_no bigint,
    next_dt_old date,
    next_dt_new date,
    tentative_cl_dt_old date,
    tentative_cl_dt_new date,
    listorder bigint NOT NULL,
    conn_key bigint,
    ent_dt timestamp with time zone,
    test2 character varying(10) NOT NULL,
    listorder_new bigint NOT NULL,
    board_type character(1) DEFAULT 'J'::bpchar,
    listtype character(1),
    reason character varying(100)
);


ALTER TABLE public.eliminated_cases OWNER TO postgres;

--
-- Name: eliminated_cases_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.eliminated_cases_a (
    diary_no bigint,
    next_dt_old date,
    next_dt_new date,
    tentative_cl_dt_old date,
    tentative_cl_dt_new date,
    listorder bigint,
    conn_key bigint,
    ent_dt timestamp with time zone,
    test2 character varying(10),
    listorder_new bigint,
    board_type character(1),
    listtype character(1),
    reason character varying(100)
);


ALTER TABLE public.eliminated_cases_a OWNER TO dev;

--
-- Name: elimination; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.elimination (
    fil_no bigint NOT NULL,
    ele_dt date,
    remark text,
    usercode bigint,
    ent_dt timestamp with time zone,
    display character(1) NOT NULL,
    id bigint NOT NULL,
    weeded_by bigint
);


ALTER TABLE public.elimination OWNER TO postgres;

--
-- Name: elimination_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.elimination_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.elimination_id_seq OWNER TO postgres;

--
-- Name: elimination_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.elimination_id_seq OWNED BY public.elimination.id;


--
-- Name: email_entire_list; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.email_entire_list (
    cl_date date NOT NULL,
    m_j_1 character(1) DEFAULT 'N'::bpchar,
    m_j_2 character(1) DEFAULT 'N'::bpchar,
    f_j_1 character(1) DEFAULT 'N'::bpchar,
    f_j_2 character(1) DEFAULT 'N'::bpchar NOT NULL,
    m_c_1 character(1) DEFAULT 'N'::bpchar NOT NULL,
    m_c_2 character(1) DEFAULT 'N'::bpchar NOT NULL,
    m_r_1 character(1) DEFAULT 'N'::bpchar NOT NULL,
    m_r_2 character(1) DEFAULT 'N'::bpchar NOT NULL,
    m_s_1 character(1) DEFAULT 'N'::bpchar NOT NULL,
    m_s_2 character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.email_entire_list OWNER TO postgres;

--
-- Name: email_hc_cl; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.email_hc_cl (
    title character varying(20) NOT NULL,
    name character varying(100) NOT NULL,
    email character varying(100),
    diary_no bigint,
    next_dt date,
    mainhead character(1) NOT NULL,
    court character varying(24),
    judges character varying(50) NOT NULL,
    roster_id bigint,
    board_type character(1) NOT NULL,
    brd_slno bigint NOT NULL,
    ent_time timestamp with time zone,
    cno character varying(80),
    jnames character varying(200) NOT NULL,
    pname character varying(100) NOT NULL,
    rname character varying(100) NOT NULL,
    qry_from character varying(40) NOT NULL,
    sent_to_smspool character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.email_hc_cl OWNER TO postgres;

--
-- Name: email_hc_cl_17042023; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.email_hc_cl_17042023 (
    title character varying(20) NOT NULL,
    name character varying(100) NOT NULL,
    email character varying(100),
    diary_no bigint,
    next_dt date,
    mainhead character(1) NOT NULL,
    court character varying(24),
    judges character varying(50) NOT NULL,
    roster_id bigint,
    board_type character(1) NOT NULL,
    brd_slno bigint NOT NULL,
    ent_time timestamp with time zone,
    cno character varying(80),
    jnames character varying(200) NOT NULL,
    pname character varying(100) NOT NULL,
    rname character varying(100) NOT NULL,
    qry_from character varying(40) NOT NULL,
    sent_to_smspool character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.email_hc_cl_17042023 OWNER TO postgres;

--
-- Name: f_1; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.f_1 (
    diary_no bigint
);


ALTER TABLE public.f_1 OWNER TO postgres;

--
-- Name: f_2; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.f_2 (
    diary_no bigint DEFAULT '0'::bigint NOT NULL
);


ALTER TABLE public.f_2 OWNER TO postgres;

--
-- Name: faster_cases; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.faster_cases (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    next_dt date,
    created_on timestamp with time zone,
    created_by bigint NOT NULL,
    last_step_id bigint,
    is_deleted smallint DEFAULT '0'::smallint NOT NULL,
    is_sent_to_new_faster smallint DEFAULT '0'::smallint,
    sent_to_new_faster_agency bigint,
    sent_to_new_faster_by bigint,
    sent_to_new_faster_on timestamp with time zone,
    sent_to_new_faster_reverted_by bigint,
    sent_to_new_faster_reverted_on timestamp with time zone
);


ALTER TABLE public.faster_cases OWNER TO postgres;

--
-- Name: faster_cases_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.faster_cases_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.faster_cases_id_seq OWNER TO postgres;

--
-- Name: faster_cases_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.faster_cases_id_seq OWNED BY public.faster_cases.id;


--
-- Name: faster_communication_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.faster_communication_details (
    id bigint NOT NULL,
    faster_cases_id bigint NOT NULL,
    stakeholder_details_id bigint,
    email_id character varying(100) NOT NULL,
    mobile_number bigint NOT NULL,
    created_on timestamp with time zone,
    created_by bigint,
    created_by_ip character varying(45),
    is_deleted smallint DEFAULT '0'::smallint NOT NULL,
    deleted_on timestamp with time zone,
    deleted_by bigint,
    email_sent smallint DEFAULT '0'::smallint,
    email_sent_on timestamp with time zone
);


ALTER TABLE public.faster_communication_details OWNER TO postgres;

--
-- Name: faster_communication_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.faster_communication_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.faster_communication_details_id_seq OWNER TO postgres;

--
-- Name: faster_communication_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.faster_communication_details_id_seq OWNED BY public.faster_communication_details.id;


--
-- Name: faster_opted; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.faster_opted (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint NOT NULL,
    next_dt date,
    mainhead character(1),
    board_type character(1),
    roster_id bigint,
    main_supp_flag boolean,
    judges character varying(222),
    user_id bigint,
    entry_date timestamp with time zone,
    user_ip character varying(45),
    is_active boolean DEFAULT true,
    deleted_by bigint,
    deleted_date timestamp with time zone,
    deleted_ip character varying(45),
    court_no bigint,
    item_number bigint
);


ALTER TABLE public.faster_opted OWNER TO postgres;

--
-- Name: faster_opted_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.faster_opted_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.faster_opted_id_seq OWNER TO postgres;

--
-- Name: faster_opted_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.faster_opted_id_seq OWNED BY public.faster_opted.id;


--
-- Name: faster_shared_document_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.faster_shared_document_details (
    id bigint NOT NULL,
    faster_cases_id bigint NOT NULL,
    tw_notice_id bigint NOT NULL,
    dated date,
    file_path character varying(200) NOT NULL,
    file_name character varying(100) NOT NULL,
    process_id bigint,
    remarks character varying(100),
    created_on timestamp with time zone,
    created_by bigint NOT NULL,
    created_by_ip character varying(45),
    is_digitally_signed smallint DEFAULT '0'::smallint NOT NULL,
    digitally_signed_on timestamp with time zone,
    is_deleted smallint DEFAULT '0'::smallint NOT NULL,
    deleted_on timestamp with time zone,
    is_digitally_certified smallint DEFAULT '0'::smallint,
    digitally_certified_on timestamp with time zone
);


ALTER TABLE public.faster_shared_document_details OWNER TO postgres;

--
-- Name: faster_shared_document_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.faster_shared_document_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.faster_shared_document_details_id_seq OWNER TO postgres;

--
-- Name: faster_shared_document_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.faster_shared_document_details_id_seq OWNED BY public.faster_shared_document_details.id;


--
-- Name: faster_transactions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.faster_transactions (
    id bigint NOT NULL,
    ref_faster_steps_id bigint NOT NULL,
    faster_cases_id bigint NOT NULL,
    faster_shared_document_details_id bigint,
    created_on timestamp with time zone,
    created_by bigint NOT NULL,
    created_by_ip character varying(45) NOT NULL,
    is_deleted smallint DEFAULT '0'::smallint NOT NULL,
    verify_otp_id bigint
);


ALTER TABLE public.faster_transactions OWNER TO postgres;

--
-- Name: faster_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.faster_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.faster_transactions_id_seq OWNER TO postgres;

--
-- Name: faster_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.faster_transactions_id_seq OWNED BY public.faster_transactions.id;


--
-- Name: fdr_records; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.fdr_records (
    id bigint NOT NULL,
    type smallint,
    document_number character varying(20),
    ec_case_id bigint,
    petitioner_name text,
    respondent_name text,
    account_number character varying(30),
    amount double precision,
    ref_section_code smallint,
    ref_bank_id smallint,
    ref_status_id smallint,
    deposit_date date,
    maturity_date date,
    order_date date,
    mode_code smallint,
    mode_document_number text,
    remarks text,
    case_number_display character varying(50),
    updated_by_id character varying(20),
    updated_by_name character varying(50),
    updated_datetime timestamp with time zone,
    ip_address character varying(16),
    is_deleted smallint,
    roi numeric(3,2),
    days bigint,
    month bigint,
    year bigint
);


ALTER TABLE public.fdr_records OWNER TO postgres;

--
-- Name: fdr_records_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.fdr_records_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.fdr_records_id_seq OWNER TO postgres;

--
-- Name: fdr_records_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.fdr_records_id_seq OWNED BY public.fdr_records.id;


--
-- Name: fh_temp_for_srno_15_05_2024; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.fh_temp_for_srno_15_05_2024 (
    sno2 double precision,
    sno double precision,
    last_head bytea,
    actual_head bigint DEFAULT '0'::bigint NOT NULL,
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    submaster_id bigint NOT NULL,
    sub_name1 character varying(250),
    sub_name2 character varying(250),
    sub_name3 character varying(250),
    sub_name4 character varying(250),
    subcode1 bigint DEFAULT 0,
    subcode2 character varying(5) DEFAULT '0'::character varying,
    subcode3 bigint DEFAULT '0'::bigint,
    subcode4 bigint DEFAULT '0'::bigint,
    diary_no_rec_date timestamp with time zone,
    n_dt character varying(10),
    next_dt character varying(10),
    clno bigint DEFAULT 0,
    brd_slno bigint DEFAULT 0 NOT NULL,
    conn_key character varying(10),
    mondayofweek date
);


ALTER TABLE public.fh_temp_for_srno_15_05_2024 OWNER TO postgres;

--
-- Name: fil_no_fh_cases_updation; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.fil_no_fh_cases_updation (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    active_fil_no character varying(16) NOT NULL,
    fil_no character varying(16),
    fil_no_old character varying(16) NOT NULL,
    pet_name character varying(100),
    res_name character varying(100),
    pet_adv_id bigint NOT NULL,
    res_adv_id bigint NOT NULL,
    actcode bigint,
    claim_amt bigint DEFAULT 0 NOT NULL,
    bench bigint,
    fixed bigint DEFAULT 0 NOT NULL,
    c_status character(1),
    fil_dt timestamp with time zone,
    active_fil_dt timestamp with time zone,
    case_pages bigint DEFAULT 0 NOT NULL,
    relief character varying(150),
    usercode bigint,
    last_usercode bigint,
    dacode bigint,
    old_dacode bigint NOT NULL,
    old_da_ec_case bigint NOT NULL,
    last_dt timestamp with time zone,
    conn_key character varying(10),
    case_grp character(3),
    lastorder text,
    fixeddet text,
    bailno character varying(1),
    prevno character varying(14),
    head_code character varying(50),
    scr_user bigint NOT NULL,
    scr_time timestamp with time zone,
    scr_type character varying(2) NOT NULL,
    prevno_fildt timestamp with time zone,
    ack_id bigint NOT NULL,
    ack_rec_dt character varying(4) NOT NULL,
    admitted character varying(60),
    outside character(1) DEFAULT 'N'::bpchar NOT NULL,
    diary_no_rec_date timestamp with time zone,
    diary_user_id bigint NOT NULL,
    ref_agency_state_id bigint NOT NULL,
    ref_agency_state_id_old bigint NOT NULL,
    ref_agency_code_id bigint NOT NULL,
    ref_agency_code_id_old bigint,
    from_court bigint NOT NULL,
    is_undertaking character varying(1),
    undertaking_doc_type bigint NOT NULL,
    undertaking_reason character varying(100) NOT NULL,
    casetype_id bigint NOT NULL,
    active_casetype_id bigint NOT NULL,
    padvt character(2) NOT NULL,
    radvt character(2) NOT NULL,
    total_court_fee bigint NOT NULL,
    court_fee bigint NOT NULL,
    valuation bigint NOT NULL,
    case_status_id bigint NOT NULL,
    brief_description character varying(500) NOT NULL,
    nature character varying(1) NOT NULL,
    fil_no_fh character varying(16) NOT NULL,
    fil_no_fh_old character varying(16) NOT NULL,
    fil_dt_fh timestamp with time zone,
    mf_active character(1) NOT NULL,
    active_reg_year bigint NOT NULL,
    reg_year_mh bigint NOT NULL,
    reg_year_fh bigint NOT NULL,
    reg_no_display text NOT NULL,
    pno bigint NOT NULL,
    rno bigint NOT NULL
);


ALTER TABLE public.fil_no_fh_cases_updation OWNER TO postgres;

--
-- Name: fil_trap; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.fil_trap (
    uid bigint NOT NULL,
    diary_no bigint NOT NULL,
    d_by_empid bigint NOT NULL,
    d_to_empid bigint NOT NULL,
    disp_dt timestamp with time zone,
    remarks character varying(200) NOT NULL,
    r_by_empid bigint NOT NULL,
    rece_dt timestamp with time zone,
    comp_dt timestamp with time zone,
    disp_dt_seq character varying(26) DEFAULT '0000-00-00 00:00:00.000000'::character varying NOT NULL,
    other bigint NOT NULL,
    scr_lower bigint DEFAULT '0'::bigint NOT NULL,
    consignment_remark text,
    token_no bigint,
    create_modify timestamp without time zone,
    updated_by bigint,
    updated_by_ip text
);


ALTER TABLE public.fil_trap OWNER TO postgres;

--
-- Name: fil_trap_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.fil_trap_a (
    uid bigint,
    diary_no bigint,
    d_by_empid bigint,
    d_to_empid bigint,
    disp_dt timestamp with time zone,
    remarks character varying(200),
    r_by_empid bigint,
    rece_dt timestamp with time zone,
    comp_dt timestamp with time zone,
    disp_dt_seq character varying(26),
    other bigint,
    scr_lower bigint,
    consignment_remark text,
    token_no bigint
);


ALTER TABLE public.fil_trap_a OWNER TO dev;

--
-- Name: fil_trap_his; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.fil_trap_his (
    uid bigint NOT NULL,
    diary_no bigint NOT NULL,
    d_by_empid bigint NOT NULL,
    d_to_empid bigint NOT NULL,
    disp_dt timestamp with time zone,
    remarks character varying(200) NOT NULL,
    r_by_empid bigint NOT NULL,
    rece_dt timestamp with time zone,
    comp_dt timestamp with time zone,
    disp_dt_seq character varying(26) DEFAULT '0000-00-00 00:00:00.000000'::character varying NOT NULL,
    thisdt timestamp with time zone,
    other bigint NOT NULL,
    scr_lower bigint DEFAULT '0'::bigint NOT NULL,
    consignment_remark text,
    token_no bigint
);


ALTER TABLE public.fil_trap_his OWNER TO postgres;

--
-- Name: fil_trap_his_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.fil_trap_his_a (
    uid bigint,
    diary_no bigint,
    d_by_empid bigint,
    d_to_empid bigint,
    disp_dt timestamp with time zone,
    remarks character varying(200),
    r_by_empid bigint,
    rece_dt timestamp with time zone,
    comp_dt timestamp with time zone,
    disp_dt_seq character varying(26),
    thisdt timestamp with time zone,
    other bigint,
    scr_lower bigint,
    consignment_remark text,
    token_no bigint
);


ALTER TABLE public.fil_trap_his_a OWNER TO dev;

--
-- Name: fil_trap_his_uid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.fil_trap_his_uid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.fil_trap_his_uid_seq OWNER TO postgres;

--
-- Name: fil_trap_his_uid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.fil_trap_his_uid_seq OWNED BY public.fil_trap_his.uid;


--
-- Name: fil_trap_refil_users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.fil_trap_refil_users (
    id bigint NOT NULL,
    ddate date,
    utype character varying(5) NOT NULL,
    no bigint NOT NULL,
    ctype bigint NOT NULL
);


ALTER TABLE public.fil_trap_refil_users OWNER TO postgres;

--
-- Name: fil_trap_refil_users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.fil_trap_refil_users_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.fil_trap_refil_users_id_seq OWNER TO postgres;

--
-- Name: fil_trap_refil_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.fil_trap_refil_users_id_seq OWNED BY public.fil_trap_refil_users.id;


--
-- Name: fil_trap_seq; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.fil_trap_seq (
    id bigint NOT NULL,
    ddate date,
    utype character varying(5) NOT NULL,
    no bigint NOT NULL,
    ctype bigint NOT NULL,
    user_type character(1),
    create_modify timestamp without time zone,
    updated_by bigint,
    updated_by_ip text
);


ALTER TABLE public.fil_trap_seq OWNER TO postgres;

--
-- Name: fil_trap_seq_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.fil_trap_seq_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.fil_trap_seq_id_seq OWNER TO postgres;

--
-- Name: fil_trap_seq_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.fil_trap_seq_id_seq OWNED BY public.fil_trap_seq.id;


--
-- Name: fil_trap_uid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.fil_trap_uid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.fil_trap_uid_seq OWNER TO postgres;

--
-- Name: fil_trap_uid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.fil_trap_uid_seq OWNED BY public.fil_trap.uid;


--
-- Name: fil_trap_users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.fil_trap_users (
    id bigint NOT NULL,
    usertype bigint,
    usercode bigint,
    display character(1) DEFAULT 'Y'::bpchar,
    entuser bigint NOT NULL,
    ent_dt timestamp with time zone,
    upuser bigint NOT NULL,
    updt timestamp with time zone,
    user_type character(1)
);


ALTER TABLE public.fil_trap_users OWNER TO postgres;

--
-- Name: fil_trap_users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.fil_trap_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.fil_trap_users_id_seq OWNER TO postgres;

--
-- Name: fil_trap_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.fil_trap_users_id_seq OWNED BY public.fil_trap_users.id;


--
-- Name: filing_remark; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.filing_remark (
    id bigint NOT NULL,
    diary_no bigint,
    counter_remark_type character varying(100),
    remark character varying(400),
    usercode bigint,
    entry_date timestamp with time zone
);


ALTER TABLE public.filing_remark OWNER TO postgres;

--
-- Name: filing_remark_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.filing_remark_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.filing_remark_id_seq OWNER TO postgres;

--
-- Name: filing_remark_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.filing_remark_id_seq OWNED BY public.filing_remark.id;


--
-- Name: filing_stats; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.filing_stats (
    filing_date date,
    updation_time time without time zone,
    total_filed bigint,
    physical_filed bigint,
    old_efiled bigint,
    refiled bigint,
    registered bigint,
    checked_verified bigint,
    verified bigint,
    tagging_verification bigint,
    verification_refiled_total bigint,
    verification_refiled_reg bigint,
    filing_alloted bigint,
    filing_completed bigint,
    filing_pending bigint,
    refiled_alloted bigint,
    refiled_completed bigint,
    refiled_pending bigint,
    id bigint NOT NULL,
    new_efiled bigint
);


ALTER TABLE public.filing_stats OWNER TO postgres;

--
-- Name: filing_stats_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.filing_stats_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.filing_stats_id_seq OWNER TO postgres;

--
-- Name: filing_stats_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.filing_stats_id_seq OWNED BY public.filing_stats.id;


--
-- Name: final_elimination_cl_printed; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.final_elimination_cl_printed (
    id bigint NOT NULL,
    next_dt date,
    board_type character(1) NOT NULL,
    usercode bigint DEFAULT '0'::bigint NOT NULL,
    ent_time timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE public.final_elimination_cl_printed OWNER TO postgres;

--
-- Name: final_elimination_cl_printed_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.final_elimination_cl_printed_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.final_elimination_cl_printed_id_seq OWNER TO postgres;

--
-- Name: final_elimination_cl_printed_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.final_elimination_cl_printed_id_seq OWNED BY public.final_elimination_cl_printed.id;


--
-- Name: free_text_rop; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.free_text_rop (
    diary_no bigint NOT NULL,
    diary_number character varying(50),
    diary_year character varying(50),
    case_type character varying(50),
    case_number character varying(50),
    case_year character varying(50),
    dated character varying(50),
    rop_text text,
    file_type character varying(50)
);


ALTER TABLE public.free_text_rop OWNER TO postgres;

--
-- Name: headfooter; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.headfooter (
    hf_id bigint NOT NULL,
    next_dt date,
    roster_id bigint,
    h_f_note text,
    h_f_flag character(1),
    usercode bigint,
    ent_dt timestamp with time zone,
    display character varying(1),
    part bigint NOT NULL,
    mainhead character varying(1) NOT NULL
);


ALTER TABLE public.headfooter OWNER TO postgres;

--
-- Name: headfooter_hf_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.headfooter_hf_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.headfooter_hf_id_seq OWNER TO postgres;

--
-- Name: headfooter_hf_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.headfooter_hf_id_seq OWNED BY public.headfooter.hf_id;


--
-- Name: heardt; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.heardt (
    diary_no bigint NOT NULL,
    conn_key bigint DEFAULT '0'::bigint NOT NULL,
    next_dt date,
    mainhead character(1),
    subhead bigint,
    clno bigint DEFAULT 0,
    brd_slno bigint DEFAULT 0 NOT NULL,
    roster_id bigint DEFAULT '0'::bigint NOT NULL,
    judges character varying(50) DEFAULT '0'::character varying NOT NULL,
    coram character varying(50) DEFAULT '0'::character varying,
    board_type character varying(10) NOT NULL,
    usercode bigint,
    ent_dt timestamp with time zone,
    module_id bigint,
    mainhead_n character(1) NOT NULL,
    subhead_n bigint NOT NULL,
    main_supp_flag bigint DEFAULT 8888 NOT NULL,
    listorder bigint NOT NULL,
    tentative_cl_dt date,
    listed_ia text,
    sitting_judges smallint DEFAULT '2'::smallint NOT NULL,
    list_before_remark bigint DEFAULT '0'::bigint NOT NULL,
    coram_prev character varying(100) DEFAULT '0'::character varying NOT NULL,
    is_nmd character(1) DEFAULT 'N'::bpchar NOT NULL,
    no_of_time_deleted bigint DEFAULT '0'::bigint
);


ALTER TABLE public.heardt OWNER TO postgres;

--
-- Name: heardt_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.heardt_a (
    diary_no bigint,
    conn_key bigint,
    next_dt date,
    mainhead character(1),
    subhead bigint,
    clno bigint,
    brd_slno bigint,
    roster_id bigint,
    judges character varying(50),
    coram character varying(50),
    board_type public.heardt_board_type,
    usercode bigint,
    ent_dt timestamp with time zone,
    module_id bigint,
    mainhead_n character(1),
    subhead_n bigint,
    main_supp_flag bigint,
    listorder bigint,
    tentative_cl_dt date,
    listed_ia text,
    sitting_judges smallint,
    list_before_remark bigint,
    coram_prev character varying(100),
    is_nmd character(1),
    no_of_time_deleted bigint
);


ALTER TABLE public.heardt_a OWNER TO dev;

--
-- Name: heardt_webuse; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.heardt_webuse (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    conn_key bigint DEFAULT '0'::bigint NOT NULL,
    next_dt date,
    mainhead character(1),
    subhead bigint,
    clno bigint DEFAULT 0,
    brd_slno bigint DEFAULT 0 NOT NULL,
    roster_id bigint DEFAULT '0'::bigint NOT NULL,
    judges character varying(50) DEFAULT '0'::character varying NOT NULL,
    coram character varying(50) DEFAULT '0'::character varying,
    board_type public.heardt_webuse_board_type NOT NULL,
    usercode bigint,
    ent_dt timestamp with time zone,
    module_id bigint,
    mainhead_n character(1) NOT NULL,
    subhead_n bigint NOT NULL,
    main_supp_flag bigint DEFAULT 8888 NOT NULL,
    listorder bigint NOT NULL,
    tentative_cl_dt date,
    listed_ia text,
    sitting_judges smallint DEFAULT '2'::smallint NOT NULL,
    list_before_remark bigint,
    coram_prev character varying(100) DEFAULT '0'::character varying NOT NULL,
    is_nmd character(1) DEFAULT 'N'::bpchar NOT NULL,
    no_of_time_deleted bigint DEFAULT '0'::bigint
);


ALTER TABLE public.heardt_webuse OWNER TO postgres;

--
-- Name: hybrid_physical_hearing_consent; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.hybrid_physical_hearing_consent (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint NOT NULL,
    consent character(1) DEFAULT 'N'::bpchar,
    hearing_from_time time without time zone,
    hearing_to_time time without time zone,
    from_dt date,
    to_dt date,
    list_type_id bigint,
    list_number bigint,
    list_year bigint,
    mainhead character(1),
    board_type character(1),
    user_id bigint,
    entry_date timestamp with time zone,
    user_ip character varying(45),
    court_no bigint DEFAULT '0'::bigint NOT NULL,
    roster_id bigint,
    main_supp_flag smallint,
    part_no smallint,
    judges character varying(50)
);


ALTER TABLE public.hybrid_physical_hearing_consent OWNER TO postgres;

--
-- Name: hybrid_physical_hearing_consent_freeze; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.hybrid_physical_hearing_consent_freeze (
    id bigint NOT NULL,
    list_type_id bigint,
    list_number bigint,
    list_year bigint,
    user_id bigint,
    entry_date timestamp with time zone,
    user_ip character varying(45),
    is_active character(1) DEFAULT 't'::bpchar,
    unfreezed_by bigint,
    unfreezed_date timestamp with time zone,
    unfreezed_user_ip character varying(45),
    to_date date,
    court_no bigint NOT NULL
);


ALTER TABLE public.hybrid_physical_hearing_consent_freeze OWNER TO postgres;

--
-- Name: hybrid_physical_hearing_consent_freeze_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.hybrid_physical_hearing_consent_freeze_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.hybrid_physical_hearing_consent_freeze_id_seq OWNER TO postgres;

--
-- Name: hybrid_physical_hearing_consent_freeze_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.hybrid_physical_hearing_consent_freeze_id_seq OWNED BY public.hybrid_physical_hearing_consent_freeze.id;


--
-- Name: hybrid_physical_hearing_consent_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.hybrid_physical_hearing_consent_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.hybrid_physical_hearing_consent_id_seq OWNER TO postgres;

--
-- Name: hybrid_physical_hearing_consent_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.hybrid_physical_hearing_consent_id_seq OWNED BY public.hybrid_physical_hearing_consent.id;


--
-- Name: hybrid_physical_hearing_consent_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.hybrid_physical_hearing_consent_log (
    id bigint DEFAULT '0'::bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint NOT NULL,
    consent character(1) DEFAULT 'N'::bpchar,
    hearing_from_time time without time zone,
    hearing_to_time time without time zone,
    from_dt date,
    to_dt date,
    list_type_id bigint,
    list_number bigint,
    list_year bigint,
    mainhead character(1),
    board_type character(1),
    user_id bigint,
    entry_date timestamp with time zone,
    user_ip character varying(45),
    court_no bigint DEFAULT '0'::bigint NOT NULL,
    roster_id bigint,
    main_supp_flag smallint,
    part_no smallint,
    judges character varying(50),
    updated_by bigint,
    updated_date timestamp with time zone,
    updated_user_ip character varying(45)
);


ALTER TABLE public.hybrid_physical_hearing_consent_log OWNER TO postgres;

--
-- Name: i1; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.i1 (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    active_fil_no character varying(16) NOT NULL,
    fil_no character varying(16),
    fil_no_old character varying(16) NOT NULL,
    pet_name character varying(100),
    res_name character varying(100),
    res_name_old character varying(100) NOT NULL,
    pet_adv_id bigint NOT NULL,
    res_adv_id bigint NOT NULL,
    actcode bigint,
    claim_amt bigint DEFAULT 0 NOT NULL,
    bench bigint,
    fixed bigint DEFAULT 0 NOT NULL,
    c_status character(1),
    fil_dt timestamp with time zone,
    active_fil_dt timestamp with time zone,
    case_pages bigint DEFAULT 0 NOT NULL,
    relief character varying(150),
    usercode bigint,
    last_usercode bigint,
    dacode bigint NOT NULL,
    old_dacode bigint NOT NULL,
    old_da_ec_case bigint NOT NULL,
    last_dt timestamp with time zone,
    conn_key character varying(10),
    case_grp character(3),
    lastorder text,
    fixeddet text,
    bailno character varying(1),
    prevno character varying(14),
    head_code character varying(50),
    scr_user bigint NOT NULL,
    scr_time timestamp with time zone,
    scr_type character varying(2) NOT NULL,
    prevno_fildt timestamp with time zone,
    ack_id bigint NOT NULL,
    ack_rec_dt character varying(4) NOT NULL,
    admitted character varying(60),
    outside character(1) DEFAULT 'N'::bpchar NOT NULL,
    diary_no_rec_date timestamp with time zone,
    diary_user_id bigint NOT NULL,
    ref_agency_state_id bigint NOT NULL,
    ref_agency_state_id_old bigint NOT NULL,
    ref_agency_code_id bigint NOT NULL,
    ref_agency_code_id_old bigint,
    from_court bigint NOT NULL,
    is_undertaking character varying(1),
    undertaking_doc_type bigint NOT NULL,
    undertaking_reason character varying(100) NOT NULL,
    casetype_id bigint NOT NULL,
    active_casetype_id bigint NOT NULL,
    padvt character(2) NOT NULL,
    radvt character(2) NOT NULL,
    total_court_fee bigint NOT NULL,
    court_fee bigint NOT NULL,
    valuation bigint NOT NULL,
    case_status_id bigint NOT NULL,
    brief_description character varying(500) NOT NULL,
    nature character varying(1) NOT NULL,
    fil_no_fh character varying(16) NOT NULL,
    fil_no_fh_old character varying(16) NOT NULL,
    fil_dt_fh timestamp with time zone,
    mf_active character(1) DEFAULT 'M'::bpchar,
    active_reg_year bigint NOT NULL,
    reg_year_mh bigint NOT NULL,
    reg_year_fh bigint NOT NULL,
    reg_no_display text NOT NULL,
    pno bigint NOT NULL,
    rno bigint NOT NULL,
    if_sclsc smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.i1 OWNER TO postgres;

--
-- Name: i2; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.i2 (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    active_fil_no character varying(16) NOT NULL,
    fil_no character varying(16),
    fil_no_old character varying(16) NOT NULL,
    pet_name character varying(100),
    res_name character varying(100),
    res_name_old character varying(100) NOT NULL,
    pet_adv_id bigint NOT NULL,
    res_adv_id bigint NOT NULL,
    actcode bigint,
    claim_amt bigint DEFAULT 0 NOT NULL,
    bench bigint,
    fixed bigint DEFAULT 0 NOT NULL,
    c_status character(1),
    fil_dt timestamp with time zone,
    active_fil_dt timestamp with time zone,
    case_pages bigint DEFAULT 0 NOT NULL,
    relief character varying(150),
    usercode bigint,
    last_usercode bigint,
    dacode bigint NOT NULL,
    old_dacode bigint NOT NULL,
    old_da_ec_case bigint NOT NULL,
    last_dt timestamp with time zone,
    conn_key character varying(10),
    case_grp character(3),
    lastorder text,
    fixeddet text,
    bailno character varying(1),
    prevno character varying(14),
    head_code character varying(50),
    scr_user bigint NOT NULL,
    scr_time timestamp with time zone,
    scr_type character varying(2) NOT NULL,
    prevno_fildt timestamp with time zone,
    ack_id bigint NOT NULL,
    ack_rec_dt character varying(4) NOT NULL,
    admitted character varying(60),
    outside character(1) DEFAULT 'N'::bpchar NOT NULL,
    diary_no_rec_date timestamp with time zone,
    diary_user_id bigint NOT NULL,
    ref_agency_state_id bigint NOT NULL,
    ref_agency_state_id_old bigint NOT NULL,
    ref_agency_code_id bigint NOT NULL,
    ref_agency_code_id_old bigint,
    from_court bigint NOT NULL,
    is_undertaking character varying(1),
    undertaking_doc_type bigint NOT NULL,
    undertaking_reason character varying(100) NOT NULL,
    casetype_id bigint NOT NULL,
    active_casetype_id bigint NOT NULL,
    padvt character(2) NOT NULL,
    radvt character(2) NOT NULL,
    total_court_fee bigint NOT NULL,
    court_fee bigint NOT NULL,
    valuation bigint NOT NULL,
    case_status_id bigint NOT NULL,
    brief_description character varying(500) NOT NULL,
    nature character varying(1) NOT NULL,
    fil_no_fh character varying(16) NOT NULL,
    fil_no_fh_old character varying(16) NOT NULL,
    fil_dt_fh timestamp with time zone,
    mf_active character(1) DEFAULT 'M'::bpchar,
    active_reg_year bigint NOT NULL,
    reg_year_mh bigint NOT NULL,
    reg_year_fh bigint NOT NULL,
    reg_no_display text NOT NULL,
    pno bigint NOT NULL,
    rno bigint NOT NULL,
    if_sclsc smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.i2 OWNER TO postgres;

--
-- Name: ia_restore_remarks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ia_restore_remarks (
    diary_no bigint NOT NULL,
    docnum bigint NOT NULL,
    docyear bigint NOT NULL,
    docd_id bigint NOT NULL,
    restoration_remarks character varying(255) NOT NULL,
    updated_by character varying(45),
    updated_on timestamp with time zone,
    ip_address character varying(100)
);


ALTER TABLE public.ia_restore_remarks OWNER TO postgres;

--
-- Name: idp_stats; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.idp_stats (
    id bigint NOT NULL,
    date date,
    total_institution bigint,
    misc_institution bigint,
    regular_institution bigint,
    civil_institution bigint,
    criminal_institution bigint,
    total_disposed bigint,
    misc_disp bigint,
    regular_disp bigint,
    civil_disp bigint,
    criminal_disp bigint,
    total_pendency bigint,
    regular_pendency bigint,
    misc_pendency bigint,
    civil_pendency bigint,
    criminal_pendency bigint,
    complete_pendency bigint,
    incomplete_pendency bigint,
    ready_pendency bigint,
    not_ready_pendency bigint,
    updated_on timestamp with time zone,
    display character varying(5) DEFAULT 'Y'::character varying,
    recalled bigint,
    recalled_dismissed bigint
);


ALTER TABLE public.idp_stats OWNER TO postgres;

--
-- Name: idp_stats_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.idp_stats_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.idp_stats_id_seq OWNER TO postgres;

--
-- Name: idp_stats_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.idp_stats_id_seq OWNED BY public.idp_stats.id;


--
-- Name: indexing; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.indexing (
    diary_no bigint,
    doccode bigint NOT NULL,
    doccode1 bigint NOT NULL,
    other character varying(500),
    i_type bigint NOT NULL,
    fp bigint,
    tp bigint,
    np bigint,
    entdt timestamp with time zone,
    ucode bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    upd_tif_dt timestamp with time zone,
    upd_tif_id bigint,
    ind_id bigint NOT NULL,
    pdf_name character varying(500) NOT NULL,
    lowerct_id bigint NOT NULL,
    src_of_ent bigint DEFAULT '0'::bigint,
    file_id character varying(25)
);


ALTER TABLE public.indexing OWNER TO postgres;

--
-- Name: indexing_ind_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.indexing_ind_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.indexing_ind_id_seq OWNER TO postgres;

--
-- Name: indexing_ind_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.indexing_ind_id_seq OWNED BY public.indexing.ind_id;


--
-- Name: invalid_disp_dt_28072018; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.invalid_disp_dt_28072018 (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    ord_dt date,
    disp_dt date
);


ALTER TABLE public.invalid_disp_dt_28072018 OWNER TO postgres;

--
-- Name: jail_petition_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jail_petition_details (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    jailer_sign_dt date,
    jail_display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    diary_no_entry_dt timestamp with time zone,
    create_modify timestamp with time zone,
    updated_on timestamp with time zone,
    updated_by bigint,
    updated_by_ip character varying
);


ALTER TABLE public.jail_petition_details OWNER TO postgres;

--
-- Name: jail_petition_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jail_petition_details_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jail_petition_details_id_seq OWNER TO postgres;

--
-- Name: jail_petition_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jail_petition_details_id_seq OWNED BY public.jail_petition_details.id;


--
-- Name: jo_alottment_paps; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jo_alottment_paps (
    usercode bigint NOT NULL,
    cl_date date,
    filno character varying(16) NOT NULL,
    display character(1) NOT NULL,
    court character varying(100) NOT NULL,
    uid bigint NOT NULL,
    ent_dt timestamp with time zone,
    mainhead character(1) NOT NULL,
    clno bigint NOT NULL,
    diary_no bigint
);


ALTER TABLE public.jo_alottment_paps OWNER TO postgres;

--
-- Name: judge_group; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.judge_group (
    id bigint NOT NULL,
    p1 bigint,
    p2 bigint,
    p3 bigint DEFAULT '0'::bigint,
    from_dt date,
    to_dt date,
    display character(1) DEFAULT 'Y'::bpchar,
    fresh_limit bigint,
    old_limit bigint,
    ent_dt timestamp with time zone,
    usercode bigint,
    to_dt_ent_dt timestamp with time zone,
    to_dt_usercode bigint
);


ALTER TABLE public.judge_group OWNER TO postgres;

--
-- Name: COLUMN judge_group.p1; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.judge_group.p1 IS '    ';


--
-- Name: judge_group1; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.judge_group1 (
    p1 bigint,
    p2 bigint,
    p3 character varying(45),
    from_dt date,
    to_dt date,
    display character(1)
);


ALTER TABLE public.judge_group1 OWNER TO postgres;

--
-- Name: COLUMN judge_group1.p1; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.judge_group1.p1 IS '    ';


--
-- Name: judge_group_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.judge_group_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.judge_group_id_seq OWNER TO postgres;

--
-- Name: judge_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.judge_group_id_seq OWNED BY public.judge_group.id;


--
-- Name: judgment_sci1; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.judgment_sci1 (
    diary_no bigint,
    diary_year bigint,
    c_type bigint NOT NULL,
    c_no character varying(200) NOT NULL,
    c_yr bigint NOT NULL,
    dated date,
    file_path character varying(1000) NOT NULL,
    file_type character varying(45),
    judgment_on date,
    judge1 character varying(200),
    judge2 character varying(200),
    judge3 character varying(200),
    judge4 character varying(200),
    judge5 character varying(200),
    if_reportable character varying(10)
);


ALTER TABLE public.judgment_sci1 OWNER TO postgres;

--
-- Name: judgment_summary; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.judgment_summary (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    summary character varying(1000),
    is_deleted character varying(2) DEFAULT 'f'::character varying,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_by_ip character varying(45),
    is_verified character varying(2) DEFAULT 'f'::character varying,
    verified_by bigint,
    verified_on timestamp with time zone,
    verified_by_ip character varying(45),
    orderdate date,
    deleted_by bigint,
    deleted_on timestamp with time zone,
    deleted_by_ip character varying(45)
);


ALTER TABLE public.judgment_summary OWNER TO postgres;

--
-- Name: judgment_summary_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.judgment_summary_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.judgment_summary_id_seq OWNER TO postgres;

--
-- Name: judgment_summary_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.judgment_summary_id_seq OWNED BY public.judgment_summary.id;


--
-- Name: judgment_summary_old; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.judgment_summary_old (
    diary_no bigint NOT NULL,
    summary character varying(1000),
    is_deleted character varying(2) DEFAULT 'f'::character varying,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_by_ip character varying(45),
    is_verified character varying(2) DEFAULT 'f'::character varying,
    verified_by bigint,
    verified_on timestamp with time zone,
    verified_by_ip character varying(45),
    orderdate date
);


ALTER TABLE public.judgment_summary_old OWNER TO postgres;

--
-- Name: jumped_filno; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jumped_filno (
    id bigint NOT NULL,
    diaryno bigint,
    year bigint NOT NULL,
    usercode bigint NOT NULL,
    ent_dt timestamp with time zone,
    reason character varying(100)
);


ALTER TABLE public.jumped_filno OWNER TO postgres;

--
-- Name: jumped_filno_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jumped_filno_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jumped_filno_id_seq OWNER TO postgres;

--
-- Name: jumped_filno_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jumped_filno_id_seq OWNED BY public.jumped_filno.id;


--
-- Name: kept_below; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.kept_below (
    kb_key character varying(14) NOT NULL,
    fil_no character varying(14) NOT NULL,
    usercode bigint DEFAULT 0 NOT NULL,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    up_user bigint NOT NULL,
    up_dt timestamp with time zone
);


ALTER TABLE public.kept_below OWNER TO postgres;

--
-- Name: last_heardt; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.last_heardt (
    diary_no bigint NOT NULL,
    conn_key bigint NOT NULL,
    next_dt date,
    mainhead character(1),
    subhead bigint,
    clno bigint DEFAULT 0,
    brd_slno bigint NOT NULL,
    roster_id bigint,
    judges character varying(50),
    coram character varying(50) DEFAULT '0'::character varying,
    board_type public.last_heardt_board_type NOT NULL,
    usercode bigint,
    ent_dt timestamp with time zone,
    module_id bigint,
    mainhead_n character(1) NOT NULL,
    subhead_n bigint NOT NULL,
    main_supp_flag bigint DEFAULT 8888 NOT NULL,
    listorder bigint NOT NULL,
    tentative_cl_dt date,
    bench_flag character(1) NOT NULL,
    lastorder text NOT NULL,
    listed_ia text,
    sitting_judges smallint DEFAULT '2'::smallint NOT NULL,
    list_before_remark bigint,
    coram_del_res character varying(50) NOT NULL,
    is_nmd character(1) DEFAULT 'N'::bpchar NOT NULL,
    no_of_time_deleted bigint DEFAULT '0'::bigint
);


ALTER TABLE public.last_heardt OWNER TO postgres;

--
-- Name: last_heardt_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.last_heardt_a (
    diary_no bigint,
    conn_key bigint,
    next_dt date,
    mainhead character(1),
    subhead bigint,
    clno bigint,
    brd_slno bigint,
    roster_id bigint,
    judges character varying(50),
    coram character varying(50),
    board_type public.last_heardt_board_type,
    usercode bigint,
    ent_dt timestamp with time zone,
    module_id bigint,
    mainhead_n character(1),
    subhead_n bigint,
    main_supp_flag bigint,
    listorder bigint,
    tentative_cl_dt date,
    bench_flag character(1),
    lastorder text,
    listed_ia text,
    sitting_judges smallint,
    list_before_remark bigint,
    coram_del_res character varying(50),
    is_nmd character(1),
    no_of_time_deleted bigint
);


ALTER TABLE public.last_heardt_a OWNER TO dev;

--
-- Name: last_heardt_webuse; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.last_heardt_webuse (
    diary_no bigint NOT NULL,
    conn_key bigint NOT NULL,
    next_dt date,
    mainhead character(1),
    subhead bigint,
    clno bigint DEFAULT 0,
    brd_slno bigint NOT NULL,
    roster_id bigint,
    judges character varying(50),
    coram character varying(50) DEFAULT '0'::character varying,
    board_type public.last_heardt_webuse_board_type NOT NULL,
    usercode bigint,
    ent_dt timestamp with time zone,
    module_id bigint,
    mainhead_n character(1) NOT NULL,
    subhead_n bigint NOT NULL,
    main_supp_flag bigint DEFAULT 8888 NOT NULL,
    listorder bigint NOT NULL,
    tentative_cl_dt date,
    bench_flag character(1) NOT NULL,
    lastorder text NOT NULL,
    listed_ia text,
    sitting_judges smallint DEFAULT '2'::smallint NOT NULL,
    list_before_remark bigint,
    coram_del_res character varying(50) NOT NULL,
    is_nmd character(1) DEFAULT 'N'::bpchar NOT NULL,
    no_of_time_deleted bigint DEFAULT '0'::bigint
);


ALTER TABLE public.last_heardt_webuse OWNER TO postgres;

--
-- Name: law_points; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.law_points (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    question_of_law character varying(21844) NOT NULL,
    catchwords character varying(50),
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15) NOT NULL,
    is_verified boolean,
    verified_on timestamp with time zone,
    verified_by bigint,
    verified_from_ip character varying(15)
);


ALTER TABLE public.law_points OWNER TO postgres;

--
-- Name: law_points_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.law_points_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.law_points_id_seq OWNER TO postgres;

--
-- Name: law_points_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.law_points_id_seq OWNED BY public.law_points.id;


--
-- Name: lct_record_dis_rec; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lct_record_dis_rec (
    id bigint NOT NULL,
    lowerct_id bigint NOT NULL,
    tw_comp_not_id bigint NOT NULL,
    lct_remark character varying(500) NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    user_id bigint NOT NULL,
    ent_date timestamp with time zone
);


ALTER TABLE public.lct_record_dis_rec OWNER TO postgres;

--
-- Name: lct_record_dis_rec_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.lct_record_dis_rec_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.lct_record_dis_rec_id_seq OWNER TO postgres;

--
-- Name: lct_record_dis_rec_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lct_record_dis_rec_id_seq OWNED BY public.lct_record_dis_rec.id;


--
-- Name: ld_move; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ld_move (
    diary_no bigint NOT NULL,
    fil_no character varying(14) NOT NULL,
    doccode bigint NOT NULL,
    doccode1 bigint NOT NULL,
    docnum bigint NOT NULL,
    docyear bigint NOT NULL,
    disp_by bigint NOT NULL,
    disp_to bigint NOT NULL,
    disp_dt timestamp with time zone,
    remarks character varying(200) NOT NULL,
    rece_by bigint NOT NULL,
    rece_dt timestamp with time zone,
    other character varying(10) NOT NULL
);


ALTER TABLE public.ld_move OWNER TO postgres;

--
-- Name: ld_move_29102018; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ld_move_29102018 (
    diary_no bigint NOT NULL,
    fil_no character varying(14) NOT NULL,
    doccode bigint NOT NULL,
    doccode1 bigint NOT NULL,
    docnum bigint NOT NULL,
    docyear bigint NOT NULL,
    disp_by bigint NOT NULL,
    disp_to bigint NOT NULL,
    disp_dt timestamp with time zone,
    remarks character varying(200) NOT NULL,
    rece_by bigint NOT NULL,
    rece_dt timestamp with time zone,
    other character varying(10) NOT NULL
);


ALTER TABLE public.ld_move_29102018 OWNER TO postgres;

--
-- Name: ld_move_30102018; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ld_move_30102018 (
    diary_no bigint NOT NULL,
    fil_no character varying(14) NOT NULL,
    doccode bigint NOT NULL,
    doccode1 bigint NOT NULL,
    docnum bigint NOT NULL,
    docyear bigint NOT NULL,
    disp_by bigint NOT NULL,
    disp_to bigint NOT NULL,
    disp_dt timestamp with time zone,
    remarks character varying(200) NOT NULL,
    rece_by bigint NOT NULL,
    rece_dt timestamp with time zone,
    other character varying(10) NOT NULL
);


ALTER TABLE public.ld_move_30102018 OWNER TO postgres;

--
-- Name: linked_cases; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.linked_cases (
    id bigint NOT NULL,
    conn_key bigint,
    diary_no bigint,
    linked_to bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL
);


ALTER TABLE public.linked_cases OWNER TO postgres;

--
-- Name: linked_cases_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.linked_cases_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.linked_cases_id_seq OWNER TO postgres;

--
-- Name: linked_cases_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.linked_cases_id_seq OWNED BY public.linked_cases.id;


--
-- Name: log_check; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.log_check (
    usercode bigint NOT NULL,
    username character varying(50) NOT NULL,
    logging timestamp with time zone,
    addr character varying(15) NOT NULL,
    id_session character varying(32),
    mac_addr character varying(20) NOT NULL
);


ALTER TABLE public.log_check OWNER TO postgres;

--
-- Name: loose_block; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.loose_block (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    reason_blk character varying(100) NOT NULL,
    usercode bigint NOT NULL,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    up_user bigint NOT NULL,
    up_dt timestamp with time zone
);


ALTER TABLE public.loose_block OWNER TO postgres;

--
-- Name: loose_block_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.loose_block_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.loose_block_id_seq OWNER TO postgres;

--
-- Name: loose_block_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.loose_block_id_seq OWNED BY public.loose_block.id;


--
-- Name: lowerct; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lowerct (
    diary_no bigint NOT NULL,
    lct_dec_dt date,
    lct_judge_desg bigint,
    lct_judge_name character varying(50),
    lctjudname2 character varying(50),
    lct_jud_id character varying(100),
    lct_jud_id1 bigint,
    lct_jud_id2 bigint,
    lct_jud_id3 bigint,
    l_dist bigint,
    polstncode bigint,
    crimeno character varying(100),
    crimeyear bigint,
    usercode bigint,
    ent_dt timestamp with time zone,
    lctjudname3 character varying(50),
    ct_code bigint,
    doi date,
    hjs_cnr character varying(16),
    ljs_doi date,
    ljs_cnr character varying(16),
    l_state bigint,
    lower_court_id bigint NOT NULL,
    lw_display character varying(1),
    brief_desc character varying(200),
    sub_law character varying(200),
    l_inddep character(2),
    l_iopb bigint,
    l_iopbn character varying(100),
    l_org bigint,
    l_orgname character varying(100),
    l_ordchno character varying(50),
    lct_casetype bigint,
    lct_caseno character varying(50),
    lct_caseyear bigint,
    is_order_challenged character varying(1),
    full_interim_flag character varying(1),
    judgement_covered_in character varying(250),
    vehicle_code bigint,
    vehicle_no character varying(7),
    cnr_no character varying(16),
    ref_court bigint,
    ref_case_type bigint,
    ref_case_no bigint,
    ref_case_year bigint,
    ref_state bigint,
    ref_district bigint,
    gov_not_state_id bigint,
    gov_not_case_type character varying(50),
    gov_not_case_no bigint,
    gov_not_case_year bigint,
    gov_not_date date,
    fir_lodge_date timestamp with time zone,
    deleted_by bigint,
    delete_datetime timestamp with time zone,
    delete_userip character varying(45),
    casetype_id bigint,
    create_modify timestamp without time zone,
    updated_by bigint,
    updated_by_ip text
);


ALTER TABLE public.lowerct OWNER TO postgres;

--
-- Name: lowerct_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.lowerct_a (
    diary_no bigint,
    lct_dec_dt date,
    lct_judge_desg bigint,
    lct_judge_name character varying(50),
    lctjudname2 character varying(50),
    lct_jud_id character varying(100),
    lct_jud_id1 bigint,
    lct_jud_id2 bigint,
    lct_jud_id3 bigint,
    l_dist bigint,
    polstncode bigint,
    crimeno character varying(100),
    crimeyear bigint,
    usercode bigint,
    ent_dt timestamp with time zone,
    lctjudname3 character varying(50),
    ct_code bigint,
    doi date,
    hjs_cnr character varying(16),
    ljs_doi date,
    ljs_cnr character varying(16),
    l_state bigint,
    lower_court_id bigint,
    lw_display character varying(1),
    brief_desc character varying(200),
    sub_law character varying(200),
    l_inddep character(2),
    l_iopb bigint,
    l_iopbn character varying(100),
    l_org bigint,
    l_orgname character varying(100),
    l_ordchno character varying(50),
    lct_casetype bigint,
    lct_caseno character varying(50),
    lct_caseyear bigint,
    is_order_challenged character varying(1),
    full_interim_flag character varying(1),
    judgement_covered_in character varying(250),
    vehicle_code bigint,
    vehicle_no character varying(7),
    cnr_no character varying(16),
    ref_court bigint,
    ref_case_type bigint,
    ref_case_no bigint,
    ref_case_year bigint,
    ref_state bigint,
    ref_district bigint,
    gov_not_state_id bigint,
    gov_not_case_type character varying(50),
    gov_not_case_no bigint,
    gov_not_case_year bigint,
    gov_not_date date,
    fir_lodge_date timestamp with time zone,
    deleted_by bigint,
    delete_datetime timestamp with time zone,
    delete_userip character varying(45)
);


ALTER TABLE public.lowerct_a OWNER TO dev;

--
-- Name: lowerct_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lowerct_history (
    diary_no bigint NOT NULL,
    lct_dec_dt date,
    lct_judge_desg bigint,
    lct_judge_name character varying(50),
    lctjudname2 character varying(50),
    lct_jud_id character varying(100) NOT NULL,
    lct_jud_id1 bigint NOT NULL,
    lct_jud_id2 bigint NOT NULL,
    lct_jud_id3 bigint NOT NULL,
    l_dist bigint,
    polstncode bigint,
    crimeno character varying(100),
    crimeyear bigint,
    usercode bigint,
    ent_dt timestamp with time zone,
    lctjudname3 character varying(50) NOT NULL,
    ct_code bigint NOT NULL,
    doi date,
    hjs_cnr character varying(16) NOT NULL,
    ljs_doi date,
    ljs_cnr character varying(16) NOT NULL,
    l_state bigint NOT NULL,
    lower_court_id bigint DEFAULT '0'::bigint NOT NULL,
    lw_display character varying(1) NOT NULL,
    brief_desc character varying(200),
    sub_law character varying(200),
    l_inddep character(2) NOT NULL,
    l_iopb bigint,
    l_iopbn character varying(100),
    l_org bigint NOT NULL,
    l_orgname character varying(100) NOT NULL,
    l_ordchno character varying(50) NOT NULL,
    lct_casetype bigint NOT NULL,
    lct_caseno character varying(50),
    lct_caseyear bigint NOT NULL,
    is_order_challenged character varying(1) NOT NULL,
    full_interim_flag character varying(1) NOT NULL,
    judgement_covered_in character varying(250) NOT NULL,
    vehicle_code bigint NOT NULL,
    vehicle_no character varying(7) NOT NULL,
    cnr_no character varying(16) NOT NULL,
    ref_court bigint NOT NULL,
    ref_case_type bigint NOT NULL,
    ref_case_no bigint NOT NULL,
    ref_case_year bigint NOT NULL,
    ref_state bigint NOT NULL,
    ref_district bigint NOT NULL,
    gov_not_state_id bigint NOT NULL,
    gov_not_case_type character varying(50),
    gov_not_case_no bigint NOT NULL,
    gov_not_case_year bigint NOT NULL,
    gov_not_date date,
    fir_lodge_date timestamp with time zone,
    deleted_by bigint,
    delete_datetime timestamp with time zone,
    delete_userip character varying(45),
    updated_by bigint NOT NULL,
    update_datetime timestamp with time zone,
    update_userip character varying(45) NOT NULL
);


ALTER TABLE public.lowerct_history OWNER TO postgres;

--
-- Name: lowerct_judges; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lowerct_judges (
    id bigint NOT NULL,
    lowerct_id bigint NOT NULL,
    judge_id bigint NOT NULL,
    lct_display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    create_modify timestamp without time zone,
    updated_by bigint,
    updated_by_ip text
);


ALTER TABLE public.lowerct_judges OWNER TO postgres;

--
-- Name: lowerct_judges_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.lowerct_judges_a (
    id bigint,
    lowerct_id bigint,
    judge_id bigint,
    lct_display character varying(1)
);


ALTER TABLE public.lowerct_judges_a OWNER TO dev;

--
-- Name: lowerct_judges_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.lowerct_judges_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.lowerct_judges_id_seq OWNER TO postgres;

--
-- Name: lowerct_judges_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lowerct_judges_id_seq OWNED BY public.lowerct_judges.id;


--
-- Name: lowerct_lower_court_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.lowerct_lower_court_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.lowerct_lower_court_id_seq OWNER TO postgres;

--
-- Name: lowerct_lower_court_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lowerct_lower_court_id_seq OWNED BY public.lowerct.lower_court_id;


--
-- Name: main; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.main (
    diary_no bigint NOT NULL,
    active_fil_no character varying(16) NOT NULL,
    fil_no character varying(16),
    fil_no_old character varying(16) NOT NULL,
    pet_name character varying(700),
    res_name character varying(700),
    res_name_old character varying(100) NOT NULL,
    pet_adv_id bigint NOT NULL,
    res_adv_id bigint NOT NULL,
    actcode bigint,
    claim_amt bigint DEFAULT 0 NOT NULL,
    bench bigint,
    fixed bigint DEFAULT 0 NOT NULL,
    c_status character(1),
    fil_dt timestamp with time zone,
    active_fil_dt timestamp with time zone,
    case_pages bigint DEFAULT 0 NOT NULL,
    relief character varying(150),
    usercode bigint,
    last_usercode bigint,
    dacode bigint NOT NULL,
    old_dacode bigint NOT NULL,
    old_da_ec_case bigint NOT NULL,
    last_dt timestamp with time zone,
    conn_key character varying(10),
    case_grp character(3),
    lastorder text,
    fixeddet text,
    bailno character varying(1),
    prevno character varying(14),
    head_code character varying(50),
    scr_user bigint NOT NULL,
    scr_time timestamp with time zone,
    scr_type character varying(2) NOT NULL,
    prevno_fildt timestamp with time zone,
    ack_id bigint NOT NULL,
    ack_rec_dt character varying(4) NOT NULL,
    admitted character varying(60),
    outside character(1) DEFAULT 'N'::bpchar NOT NULL,
    diary_no_rec_date timestamp with time zone,
    diary_user_id bigint NOT NULL,
    ref_agency_state_id bigint NOT NULL,
    ref_agency_state_id_old bigint NOT NULL,
    ref_agency_code_id bigint NOT NULL,
    ref_agency_code_id_old bigint,
    from_court bigint NOT NULL,
    is_undertaking character varying(1),
    undertaking_doc_type bigint NOT NULL,
    undertaking_reason character varying(100) NOT NULL,
    casetype_id bigint NOT NULL,
    active_casetype_id bigint NOT NULL,
    padvt character(2) NOT NULL,
    radvt character(2) NOT NULL,
    total_court_fee bigint NOT NULL,
    court_fee bigint NOT NULL,
    valuation bigint NOT NULL,
    case_status_id bigint NOT NULL,
    brief_description character varying(500) NOT NULL,
    nature character varying(1) NOT NULL,
    fil_no_fh character varying(16) NOT NULL,
    fil_no_fh_old character varying(16) NOT NULL,
    fil_dt_fh timestamp with time zone,
    mf_active character(1) DEFAULT 'M'::bpchar,
    active_reg_year bigint NOT NULL,
    reg_year_mh bigint NOT NULL,
    reg_year_fh bigint NOT NULL,
    reg_no_display text NOT NULL,
    pno bigint NOT NULL,
    rno bigint NOT NULL,
    if_sclsc smallint DEFAULT '0'::smallint NOT NULL,
    section_id bigint,
    unreg_fil_dt date,
    refiling_attempt timestamp with time zone,
    last_return_to_adv timestamp with time zone,
    create_modify timestamp with time zone,
    pet_name_hindi character varying(700),
    hindi_timestamp timestamp with time zone,
    res_name_hindi character varying(700),
    updated_by_ip character varying(700),
    updated_by bigint,
    updated_on character varying(700)
);


ALTER TABLE public.main OWNER TO postgres;

--
-- Name: main_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.main_a (
    diary_no bigint,
    active_fil_no character varying(16),
    fil_no character varying(16),
    fil_no_old character varying(16),
    pet_name character varying(700),
    res_name character varying(700),
    res_name_old character varying(100),
    pet_adv_id bigint,
    res_adv_id bigint,
    actcode bigint,
    claim_amt bigint,
    bench bigint,
    fixed bigint,
    c_status character(1),
    fil_dt timestamp with time zone,
    active_fil_dt timestamp with time zone,
    case_pages bigint,
    relief character varying(150),
    usercode bigint,
    last_usercode bigint,
    dacode bigint,
    old_dacode bigint,
    old_da_ec_case bigint,
    last_dt timestamp with time zone,
    conn_key character varying(10),
    case_grp character(3),
    lastorder text,
    fixeddet text,
    bailno character varying(1),
    prevno character varying(14),
    head_code character varying(50),
    scr_user bigint,
    scr_time timestamp with time zone,
    scr_type character varying(2),
    prevno_fildt timestamp with time zone,
    ack_id bigint,
    ack_rec_dt character varying(4),
    admitted character varying(60),
    outside character(1),
    diary_no_rec_date timestamp with time zone,
    diary_user_id bigint,
    ref_agency_state_id bigint,
    ref_agency_state_id_old bigint,
    ref_agency_code_id bigint,
    ref_agency_code_id_old bigint,
    from_court bigint,
    is_undertaking character varying(1),
    undertaking_doc_type bigint,
    undertaking_reason character varying(100),
    casetype_id bigint,
    active_casetype_id bigint,
    padvt character(2),
    radvt character(2),
    total_court_fee bigint,
    court_fee bigint,
    valuation bigint,
    case_status_id bigint,
    brief_description character varying(500),
    nature character varying(1),
    fil_no_fh character varying(16),
    fil_no_fh_old character varying(16),
    fil_dt_fh timestamp with time zone,
    mf_active character(1),
    active_reg_year bigint,
    reg_year_mh bigint,
    reg_year_fh bigint,
    reg_no_display text,
    pno bigint,
    rno bigint,
    if_sclsc smallint,
    section_id bigint,
    unreg_fil_dt date,
    refiling_attempt timestamp with time zone,
    last_return_to_adv timestamp with time zone,
    create_modify timestamp with time zone,
    pet_name_hindi character varying(700),
    hindi_timestamp timestamp with time zone,
    res_name_hindi character varying(700)
);


ALTER TABLE public.main_a OWNER TO dev;

--
-- Name: main_backup_data_correction; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.main_backup_data_correction (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    active_fil_no character varying(16) NOT NULL,
    fil_no character varying(16),
    fil_no_old character varying(16) NOT NULL,
    pet_name character varying(700),
    res_name character varying(700),
    res_name_old character varying(100) NOT NULL,
    pet_adv_id bigint NOT NULL,
    res_adv_id bigint NOT NULL,
    actcode bigint,
    claim_amt bigint DEFAULT 0 NOT NULL,
    bench bigint,
    fixed bigint DEFAULT 0 NOT NULL,
    c_status character(1),
    fil_dt timestamp with time zone,
    active_fil_dt timestamp with time zone,
    case_pages bigint DEFAULT 0 NOT NULL,
    relief character varying(150),
    usercode bigint,
    last_usercode bigint,
    dacode bigint NOT NULL,
    old_dacode bigint NOT NULL,
    old_da_ec_case bigint NOT NULL,
    last_dt timestamp with time zone,
    conn_key character varying(10),
    case_grp character(3),
    lastorder text,
    fixeddet text,
    bailno character varying(1),
    prevno character varying(14),
    head_code character varying(50),
    scr_user bigint NOT NULL,
    scr_time timestamp with time zone,
    scr_type character varying(2) NOT NULL,
    prevno_fildt timestamp with time zone,
    ack_id bigint NOT NULL,
    ack_rec_dt character varying(4) NOT NULL,
    admitted character varying(60),
    outside character(1) DEFAULT 'N'::bpchar NOT NULL,
    diary_no_rec_date timestamp with time zone,
    diary_user_id bigint NOT NULL,
    ref_agency_state_id bigint NOT NULL,
    ref_agency_state_id_old bigint NOT NULL,
    ref_agency_code_id bigint NOT NULL,
    ref_agency_code_id_old bigint,
    from_court bigint NOT NULL,
    is_undertaking character varying(1),
    undertaking_doc_type bigint NOT NULL,
    undertaking_reason character varying(100) NOT NULL,
    casetype_id bigint NOT NULL,
    active_casetype_id bigint NOT NULL,
    padvt character(2) NOT NULL,
    radvt character(2) NOT NULL,
    total_court_fee bigint NOT NULL,
    court_fee bigint NOT NULL,
    valuation bigint NOT NULL,
    case_status_id bigint NOT NULL,
    brief_description character varying(500) NOT NULL,
    nature character varying(1) NOT NULL,
    fil_no_fh character varying(16) NOT NULL,
    fil_no_fh_old character varying(16) NOT NULL,
    fil_dt_fh timestamp with time zone,
    mf_active character(1) DEFAULT 'M'::bpchar,
    active_reg_year bigint NOT NULL,
    reg_year_mh bigint NOT NULL,
    reg_year_fh bigint NOT NULL,
    reg_no_display text NOT NULL,
    pno bigint NOT NULL,
    rno bigint NOT NULL,
    if_sclsc smallint DEFAULT '0'::smallint NOT NULL,
    section_id bigint,
    unreg_fil_dt date,
    refiling_attempt timestamp with time zone,
    last_return_to_adv timestamp with time zone,
    create_modify timestamp with time zone
);


ALTER TABLE public.main_backup_data_correction OWNER TO postgres;

--
-- Name: main_cancel_reg; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.main_cancel_reg (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    active_fil_no character varying(16) NOT NULL,
    fil_no character varying(16),
    fil_no_old character varying(16) NOT NULL,
    pet_name character varying(100),
    res_name character varying(100),
    res_name_old character varying(100) NOT NULL,
    pet_adv_id bigint NOT NULL,
    res_adv_id bigint NOT NULL,
    actcode bigint,
    claim_amt bigint DEFAULT 0 NOT NULL,
    bench bigint,
    fixed bigint DEFAULT 0 NOT NULL,
    c_status character(1),
    fil_dt timestamp with time zone,
    active_fil_dt timestamp with time zone,
    case_pages bigint DEFAULT 0 NOT NULL,
    relief character varying(150),
    usercode bigint,
    last_usercode bigint,
    dacode bigint NOT NULL,
    old_dacode bigint NOT NULL,
    old_da_ec_case bigint NOT NULL,
    last_dt timestamp with time zone,
    conn_key character varying(10),
    case_grp character(3),
    lastorder text,
    fixeddet text,
    bailno character varying(1),
    prevno character varying(14),
    head_code character varying(50),
    scr_user bigint NOT NULL,
    scr_time timestamp with time zone,
    scr_type character varying(2) NOT NULL,
    prevno_fildt timestamp with time zone,
    ack_id bigint NOT NULL,
    ack_rec_dt character varying(4) NOT NULL,
    admitted character varying(60),
    outside character(1) DEFAULT 'N'::bpchar NOT NULL,
    diary_no_rec_date timestamp with time zone,
    diary_user_id bigint NOT NULL,
    ref_agency_state_id bigint NOT NULL,
    ref_agency_state_id_old bigint NOT NULL,
    ref_agency_code_id bigint NOT NULL,
    ref_agency_code_id_old bigint,
    from_court bigint NOT NULL,
    is_undertaking character varying(1),
    undertaking_doc_type bigint NOT NULL,
    undertaking_reason character varying(100) NOT NULL,
    casetype_id bigint NOT NULL,
    active_casetype_id bigint NOT NULL,
    padvt character(2) NOT NULL,
    radvt character(2) NOT NULL,
    total_court_fee bigint NOT NULL,
    court_fee bigint NOT NULL,
    valuation bigint NOT NULL,
    case_status_id bigint NOT NULL,
    brief_description character varying(500) NOT NULL,
    nature character varying(1) NOT NULL,
    fil_no_fh character varying(16) NOT NULL,
    fil_no_fh_old character varying(16) NOT NULL,
    fil_dt_fh timestamp with time zone,
    mf_active character(1) DEFAULT 'M'::bpchar,
    active_reg_year bigint NOT NULL,
    reg_year_mh bigint NOT NULL,
    reg_year_fh bigint NOT NULL,
    reg_no_display text NOT NULL,
    pno bigint NOT NULL,
    rno bigint NOT NULL,
    if_sclsc smallint DEFAULT '0'::smallint NOT NULL,
    section_id bigint,
    unreg_fil_dt date,
    cancel_by bigint,
    cancel_on timestamp with time zone,
    cancel_ip character varying(150)
);


ALTER TABLE public.main_cancel_reg OWNER TO postgres;

--
-- Name: main_case_diplay_changes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.main_case_diplay_changes (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    active_fil_no character varying(16) NOT NULL,
    fil_no character varying(16),
    fil_no_old character varying(16) NOT NULL,
    pet_name character varying(100),
    res_name character varying(100),
    res_name_old character varying(100) NOT NULL,
    pet_adv_id bigint NOT NULL,
    res_adv_id bigint NOT NULL,
    actcode bigint,
    claim_amt bigint DEFAULT 0 NOT NULL,
    bench bigint,
    fixed bigint DEFAULT 0 NOT NULL,
    c_status character(1),
    fil_dt timestamp with time zone,
    active_fil_dt timestamp with time zone,
    case_pages bigint DEFAULT 0 NOT NULL,
    relief character varying(150),
    usercode bigint,
    last_usercode bigint,
    dacode bigint NOT NULL,
    old_dacode bigint NOT NULL,
    old_da_ec_case bigint NOT NULL,
    last_dt timestamp with time zone,
    conn_key character varying(10),
    case_grp character(3),
    lastorder text,
    fixeddet text,
    bailno character varying(1),
    prevno character varying(14),
    head_code character varying(50),
    scr_user bigint NOT NULL,
    scr_time timestamp with time zone,
    scr_type character varying(2) NOT NULL,
    prevno_fildt timestamp with time zone,
    ack_id bigint NOT NULL,
    ack_rec_dt character varying(4) NOT NULL,
    admitted character varying(60),
    outside character(1) DEFAULT 'N'::bpchar NOT NULL,
    diary_no_rec_date timestamp with time zone,
    diary_user_id bigint NOT NULL,
    ref_agency_state_id bigint NOT NULL,
    ref_agency_state_id_old bigint NOT NULL,
    ref_agency_code_id bigint NOT NULL,
    ref_agency_code_id_old bigint,
    from_court bigint NOT NULL,
    is_undertaking character varying(1),
    undertaking_doc_type bigint NOT NULL,
    undertaking_reason character varying(100) NOT NULL,
    casetype_id bigint NOT NULL,
    active_casetype_id bigint NOT NULL,
    padvt character(2) NOT NULL,
    radvt character(2) NOT NULL,
    total_court_fee bigint NOT NULL,
    court_fee bigint NOT NULL,
    valuation bigint NOT NULL,
    case_status_id bigint NOT NULL,
    brief_description character varying(500) NOT NULL,
    nature character varying(1) NOT NULL,
    fil_no_fh character varying(16) NOT NULL,
    fil_no_fh_old character varying(16) NOT NULL,
    fil_dt_fh timestamp with time zone,
    mf_active character(1) NOT NULL,
    active_reg_year bigint NOT NULL,
    reg_year_mh bigint NOT NULL,
    reg_year_fh bigint NOT NULL,
    reg_no_display text NOT NULL,
    pno bigint NOT NULL,
    rno bigint NOT NULL,
    if_sclsc smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.main_case_diplay_changes OWNER TO postgres;

--
-- Name: main_casetype_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.main_casetype_history (
    diary_no bigint,
    id bigint NOT NULL,
    old_registration_number character varying(16),
    old_registration_year bigint,
    new_registration_number character varying(16),
    new_registration_year bigint,
    order_date timestamp with time zone,
    ref_old_case_type_id bigint,
    ref_new_case_type_id bigint,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted character varying(5) NOT NULL,
    ec_case_id bigint,
    remark character varying(500),
    create_modify timestamp with time zone
);


ALTER TABLE public.main_casetype_history OWNER TO postgres;

--
-- Name: main_casetype_history_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.main_casetype_history_a (
    diary_no bigint,
    id bigint,
    old_registration_number character varying(16),
    old_registration_year bigint,
    new_registration_number character varying(16),
    new_registration_year bigint,
    order_date timestamp with time zone,
    ref_old_case_type_id bigint,
    ref_new_case_type_id bigint,
    adm_updated_by bigint,
    updated_on timestamp with time zone,
    is_deleted character varying(5),
    ec_case_id bigint,
    remark character varying(500),
    create_modify timestamp with time zone
);


ALTER TABLE public.main_casetype_history_a OWNER TO dev;

--
-- Name: main_casetype_history_backup_data_correction; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.main_casetype_history_backup_data_correction (
    diary_no bigint,
    id bigint NOT NULL,
    old_registration_number character varying(16),
    old_registration_year bigint,
    new_registration_number character varying(16),
    new_registration_year bigint,
    order_date timestamp with time zone,
    ref_old_case_type_id bigint,
    ref_new_case_type_id bigint,
    adm_updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted character varying(5) NOT NULL,
    ec_case_id bigint,
    remark character varying(500)
);


ALTER TABLE public.main_casetype_history_backup_data_correction OWNER TO postgres;

--
-- Name: main_casetype_history_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.main_casetype_history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.main_casetype_history_id_seq OWNER TO postgres;

--
-- Name: main_casetype_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.main_casetype_history_id_seq OWNED BY public.main_casetype_history.id;


--
-- Name: main_deleted_cases; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.main_deleted_cases (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    active_fil_no character varying(16) NOT NULL,
    fil_no character varying(16),
    fil_no_old character varying(16) NOT NULL,
    pet_name character varying(100),
    res_name character varying(100),
    res_name_old character varying(100) NOT NULL,
    pet_adv_id bigint NOT NULL,
    res_adv_id bigint NOT NULL,
    actcode bigint,
    claim_amt bigint DEFAULT 0 NOT NULL,
    bench bigint,
    fixed bigint DEFAULT 0 NOT NULL,
    c_status character(1),
    fil_dt timestamp with time zone,
    active_fil_dt timestamp with time zone,
    case_pages bigint DEFAULT 0 NOT NULL,
    relief character varying(150),
    usercode bigint,
    last_usercode bigint,
    dacode bigint NOT NULL,
    old_dacode bigint NOT NULL,
    old_da_ec_case bigint NOT NULL,
    last_dt timestamp with time zone,
    conn_key character varying(10),
    case_grp character(3),
    lastorder text,
    fixeddet text,
    bailno character varying(1),
    prevno character varying(14),
    head_code character varying(50),
    scr_user bigint NOT NULL,
    scr_time timestamp with time zone,
    scr_type character varying(2) NOT NULL,
    prevno_fildt timestamp with time zone,
    ack_id bigint NOT NULL,
    ack_rec_dt character varying(4) NOT NULL,
    admitted character varying(60),
    outside character(1) DEFAULT 'N'::bpchar NOT NULL,
    diary_no_rec_date timestamp with time zone,
    diary_user_id bigint NOT NULL,
    ref_agency_state_id bigint NOT NULL,
    ref_agency_state_id_old bigint NOT NULL,
    ref_agency_code_id bigint NOT NULL,
    ref_agency_code_id_old bigint,
    from_court bigint NOT NULL,
    is_undertaking character varying(1),
    undertaking_doc_type bigint NOT NULL,
    undertaking_reason character varying(100) NOT NULL,
    casetype_id bigint NOT NULL,
    active_casetype_id bigint NOT NULL,
    padvt character(2) NOT NULL,
    radvt character(2) NOT NULL,
    total_court_fee bigint NOT NULL,
    court_fee bigint NOT NULL,
    valuation bigint NOT NULL,
    case_status_id bigint NOT NULL,
    brief_description character varying(500) NOT NULL,
    nature character varying(1) NOT NULL,
    fil_no_fh character varying(16) NOT NULL,
    fil_no_fh_old character varying(16) NOT NULL,
    fil_dt_fh timestamp with time zone,
    mf_active character(1) DEFAULT 'M'::bpchar,
    active_reg_year bigint NOT NULL,
    reg_year_mh bigint NOT NULL,
    reg_year_fh bigint NOT NULL,
    reg_no_display text NOT NULL,
    pno bigint NOT NULL,
    rno bigint NOT NULL,
    if_sclsc smallint DEFAULT '0'::smallint NOT NULL,
    section_id bigint,
    unreg_fil_dt date,
    refiling_attempt timestamp with time zone,
    last_return_to_adv timestamp with time zone,
    create_modify timestamp with time zone,
    pet_name_hindi character varying(700),
    hindi_timestamp timestamp with time zone,
    res_name_hindi character varying(700),
    deleted_on timestamp with time zone,
    deleted_reason character varying(255) NOT NULL,
    updated_by bigint,
    updated_by_ip text
);


ALTER TABLE public.main_deleted_cases OWNER TO postgres;

--
-- Name: main_ingestion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.main_ingestion (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    active_fil_no character varying(16) NOT NULL,
    fil_no character varying(16),
    fil_no_old character varying(16) NOT NULL,
    pet_name character varying(700),
    res_name character varying(700),
    res_name_old character varying(100) NOT NULL,
    pet_adv_id bigint NOT NULL,
    res_adv_id bigint NOT NULL,
    actcode bigint,
    claim_amt bigint DEFAULT 0 NOT NULL,
    bench bigint,
    fixed bigint DEFAULT 0 NOT NULL,
    c_status character(1),
    fil_dt timestamp with time zone,
    active_fil_dt timestamp with time zone,
    case_pages bigint DEFAULT 0 NOT NULL,
    relief character varying(150),
    usercode bigint,
    last_usercode bigint,
    dacode bigint NOT NULL,
    old_dacode bigint NOT NULL,
    old_da_ec_case bigint NOT NULL,
    last_dt timestamp with time zone,
    conn_key character varying(10),
    case_grp character(3),
    lastorder text,
    fixeddet text,
    bailno character varying(1),
    prevno character varying(14),
    head_code character varying(50),
    scr_user bigint NOT NULL,
    scr_time timestamp with time zone,
    scr_type character varying(2) NOT NULL,
    prevno_fildt timestamp with time zone,
    ack_id bigint NOT NULL,
    ack_rec_dt character varying(4) NOT NULL,
    admitted character varying(60),
    outside character(1) DEFAULT 'N'::bpchar NOT NULL,
    diary_no_rec_date timestamp with time zone,
    diary_user_id bigint NOT NULL,
    ref_agency_state_id bigint NOT NULL,
    ref_agency_state_id_old bigint NOT NULL,
    ref_agency_code_id bigint NOT NULL,
    ref_agency_code_id_old bigint,
    from_court bigint NOT NULL,
    is_undertaking character varying(1),
    undertaking_doc_type bigint NOT NULL,
    undertaking_reason character varying(100) NOT NULL,
    casetype_id bigint NOT NULL,
    active_casetype_id bigint NOT NULL,
    padvt character(2) NOT NULL,
    radvt character(2) NOT NULL,
    total_court_fee bigint NOT NULL,
    court_fee bigint NOT NULL,
    valuation bigint NOT NULL,
    case_status_id bigint NOT NULL,
    brief_description character varying(500) NOT NULL,
    nature character varying(1) NOT NULL,
    fil_no_fh character varying(16) NOT NULL,
    fil_no_fh_old character varying(16) NOT NULL,
    fil_dt_fh timestamp with time zone,
    mf_active character(1) DEFAULT 'M'::bpchar,
    active_reg_year bigint NOT NULL,
    reg_year_mh bigint NOT NULL,
    reg_year_fh bigint NOT NULL,
    reg_no_display text NOT NULL,
    pno bigint NOT NULL,
    rno bigint NOT NULL,
    if_sclsc smallint DEFAULT '0'::smallint NOT NULL,
    section_id bigint,
    unreg_fil_dt date,
    refiling_attempt timestamp with time zone,
    last_return_to_adv timestamp with time zone
);


ALTER TABLE public.main_ingestion OWNER TO postgres;

--
-- Name: main_section_update; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.main_section_update (
    diary_no bigint NOT NULL,
    section_id bigint,
    section_name character varying(45),
    icmis_section_id bigint
);


ALTER TABLE public.main_section_update OWNER TO postgres;

--
-- Name: mark_all_for_hc; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mark_all_for_hc (
    id bigint NOT NULL,
    mark_all character(1) DEFAULT 'Y'::bpchar NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    ent_dt timestamp with time zone,
    upd_dt timestamp with time zone
);


ALTER TABLE public.mark_all_for_hc OWNER TO postgres;

--
-- Name: mark_all_for_hc_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.mark_all_for_hc_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.mark_all_for_hc_id_seq OWNER TO postgres;

--
-- Name: mark_all_for_hc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mark_all_for_hc_id_seq OWNED BY public.mark_all_for_hc.id;


--
-- Name: mark_all_for_scrutiny; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mark_all_for_scrutiny (
    id bigint NOT NULL,
    mark_all character(1) DEFAULT 'Y'::bpchar NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    ent_dt timestamp with time zone,
    upd_dt timestamp with time zone
);


ALTER TABLE public.mark_all_for_scrutiny OWNER TO postgres;

--
-- Name: mark_all_for_scrutiny_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.mark_all_for_scrutiny_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.mark_all_for_scrutiny_id_seq OWNER TO postgres;

--
-- Name: mark_all_for_scrutiny_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mark_all_for_scrutiny_id_seq OWNED BY public.mark_all_for_scrutiny.id;


--
-- Name: matched_disposal_data; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.matched_disposal_data (
    case_type text,
    case_number text,
    case_year bigint,
    date_of_decision text,
    diary_no bigint,
    disp_dt text
);


ALTER TABLE public.matched_disposal_data OWNER TO postgres;

--
-- Name: matters_auto_updated; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.matters_auto_updated (
    main_matter bigint NOT NULL,
    connected_matters character varying(45000),
    count_connected_matters bigint
);


ALTER TABLE public.matters_auto_updated OWNER TO postgres;

--
-- Name: matters_with_wrong_section; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.matters_with_wrong_section (
    id bigint NOT NULL,
    diary_no bigint,
    dacode bigint,
    da_section_id bigint,
    matter_section_id bigint,
    ent_by bigint,
    ent_on timestamp with time zone
);


ALTER TABLE public.matters_with_wrong_section OWNER TO postgres;

--
-- Name: matters_with_wrong_section_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.matters_with_wrong_section_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.matters_with_wrong_section_id_seq OWNER TO postgres;

--
-- Name: matters_with_wrong_section_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.matters_with_wrong_section_id_seq OWNED BY public.matters_with_wrong_section.id;


--
-- Name: mention_memo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mention_memo (
    diary_no character varying(15) NOT NULL,
    date_of_received date,
    date_on_decided date,
    date_for_decided date,
    result character(1) NOT NULL,
    date_of_entry timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    user_id bigint NOT NULL,
    update_time timestamp with time zone,
    update_user bigint,
    spl_remark text NOT NULL,
    note_remark text,
    pdfname character varying(200),
    upload_date timestamp with time zone,
    uploadby bigint,
    upld_dt date,
    for_court character(1) NOT NULL,
    m_roster_id bigint,
    m_brd_slno bigint,
    m_conn_key bigint,
    id bigint NOT NULL
);


ALTER TABLE public.mention_memo OWNER TO postgres;

--
-- Name: COLUMN mention_memo.result; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.mention_memo.result IS 'A=Allow,R=Reject,N=No Order';


--
-- Name: mention_memo_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mention_memo_history (
    diary_no bigint,
    date_of_received date,
    date_on_decided date,
    date_for_decided character varying(255),
    result character varying(5),
    date_of_entry timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    user_id bigint,
    update_time character varying(255),
    update_user bigint,
    spl_remark text,
    note_remark text,
    pdfname character varying(200),
    upload_date timestamp with time zone,
    uploadby bigint,
    upld_dt date,
    for_court character varying(5),
    m_roster_id bigint,
    m_brd_slno bigint,
    m_conn_key bigint,
    event_type character(1),
    action_perform_on character varying(50),
    id bigint,
    ipaddress character varying(20) NOT NULL
);


ALTER TABLE public.mention_memo_history OWNER TO postgres;

--
-- Name: mention_memo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.mention_memo_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.mention_memo_id_seq OWNER TO postgres;

--
-- Name: mention_memo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mention_memo_id_seq OWNED BY public.mention_memo.id;


--
-- Name: mobile_numbers_wa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mobile_numbers_wa (
    id bigint NOT NULL,
    user_name character varying(100),
    mobile_number character varying(15) NOT NULL,
    causelist_pdf_allowed boolean DEFAULT false,
    create_on timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    updated_on timestamp with time zone,
    user_type bigint DEFAULT '0'::bigint
);


ALTER TABLE public.mobile_numbers_wa OWNER TO postgres;

--
-- Name: mobile_numbers_wa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.mobile_numbers_wa_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.mobile_numbers_wa_id_seq OWNER TO postgres;

--
-- Name: mobile_numbers_wa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mobile_numbers_wa_id_seq OWNED BY public.mobile_numbers_wa.id;


--
-- Name: module_entry_session; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.module_entry_session (
    session_id character varying(220) NOT NULL,
    user_id bigint NOT NULL,
    diary_no bigint,
    entry_time timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    module_id bigint NOT NULL
);


ALTER TABLE public.module_entry_session OWNER TO postgres;

--
-- Name: msg; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.msg (
    id bigint NOT NULL,
    to_user text NOT NULL,
    from_user text NOT NULL,
    msg text NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    display2 character(1) DEFAULT 'Y'::bpchar,
    trash character(1) DEFAULT 'N'::bpchar,
    trash2 character(1) DEFAULT 'N'::bpchar NOT NULL,
    "time" timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    ipadd character varying(15) NOT NULL,
    r_unr bigint DEFAULT '1'::bigint,
    seen public.msg_seen DEFAULT 'N'::public.msg_seen,
    seen_time timestamp with time zone
);


ALTER TABLE public.msg OWNER TO postgres;

--
-- Name: msg_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.msg_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.msg_id_seq OWNER TO postgres;

--
-- Name: msg_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.msg_id_seq OWNED BY public.msg.id;


--
-- Name: mul_category; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mul_category (
    diary_no bigint,
    submaster_id bigint NOT NULL,
    mul_category_idd bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    od_cat bigint NOT NULL,
    e_date timestamp with time zone,
    mul_cat_user_code bigint NOT NULL,
    new_submaster_id bigint,
    updated_on timestamp with time zone,
    updated_by bigint,
    create_modify timestamp without time zone,
    updated_by_ip text
);


ALTER TABLE public.mul_category OWNER TO postgres;

--
-- Name: mul_category_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.mul_category_a (
    diary_no bigint,
    submaster_id bigint,
    mul_category_idd bigint,
    display character varying(1),
    od_cat bigint,
    e_date timestamp with time zone,
    mul_cat_user_code bigint,
    new_submaster_id bigint,
    updated_on timestamp with time zone,
    updated_by bigint
);


ALTER TABLE public.mul_category_a OWNER TO dev;

--
-- Name: mul_category_caveat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mul_category_caveat (
    caveat_no bigint,
    diary_no bigint,
    submaster_id bigint NOT NULL,
    mul_category_idd bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    flag character varying(1) DEFAULT NULL::character varying,
    od_cat bigint NOT NULL,
    e_date timestamp with time zone,
    mul_cat_user_code bigint NOT NULL,
    new_submaster_id bigint,
    updated_on timestamp with time zone,
    updated_by bigint
);


ALTER TABLE public.mul_category_caveat OWNER TO postgres;

--
-- Name: mul_category_caveat_mul_category_idd_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.mul_category_caveat_mul_category_idd_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.mul_category_caveat_mul_category_idd_seq OWNER TO postgres;

--
-- Name: mul_category_caveat_mul_category_idd_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mul_category_caveat_mul_category_idd_seq OWNED BY public.mul_category_caveat.mul_category_idd;


--
-- Name: mul_category_mul_category_idd_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.mul_category_mul_category_idd_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.mul_category_mul_category_idd_seq OWNER TO postgres;

--
-- Name: mul_category_mul_category_idd_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mul_category_mul_category_idd_seq OWNED BY public.mul_category.mul_category_idd;


--
-- Name: neutral_citation; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.neutral_citation (
    id bigint NOT NULL,
    diary_no bigint,
    nc_number bigint,
    nc_year bigint,
    nc_display character varying(45),
    updated_by bigint,
    updated_on timestamp with time zone,
    is_deleted character varying(2) DEFAULT 'f'::character varying,
    active_casetype_id bigint,
    active_fil_no character varying(45),
    active_reg_year bigint,
    pet_name character varying(700),
    res_name character varying(700),
    dispose_order_date date,
    reg_no_display text,
    order_type character varying(5),
    coram character varying(100),
    no_of_judges bigint,
    judgment_pronounced_by bigint
);


ALTER TABLE public.neutral_citation OWNER TO postgres;

--
-- Name: neutral_citation_01072023; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.neutral_citation_01072023 (
    id bigint NOT NULL,
    diary_no bigint,
    nc_number bigint,
    nc_year bigint,
    nc_display character varying(45),
    updated_by bigint,
    updated_on timestamp with time zone,
    is_deleted character varying(2) DEFAULT 'f'::character varying,
    active_casetype_id bigint,
    active_fil_no character varying(45),
    active_reg_year bigint,
    pet_name character varying(700),
    res_name character varying(700),
    dispose_order_date date,
    reg_no_display text,
    order_type character varying(5),
    coram character varying(100),
    no_of_judges bigint
);


ALTER TABLE public.neutral_citation_01072023 OWNER TO postgres;

--
-- Name: neutral_citation_01072023_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.neutral_citation_01072023_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.neutral_citation_01072023_id_seq OWNER TO postgres;

--
-- Name: neutral_citation_01072023_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.neutral_citation_01072023_id_seq OWNED BY public.neutral_citation_01072023.id;


--
-- Name: neutral_citation_06072023; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.neutral_citation_06072023 (
    id bigint NOT NULL,
    diary_no bigint,
    nc_number bigint,
    nc_year bigint,
    nc_display character varying(45),
    updated_by bigint,
    updated_on timestamp with time zone,
    is_deleted character varying(2) DEFAULT 'f'::character varying,
    active_casetype_id bigint,
    active_fil_no character varying(45),
    active_reg_year bigint,
    pet_name character varying(700),
    res_name character varying(700),
    dispose_order_date date,
    reg_no_display text,
    order_type character varying(5),
    coram character varying(100),
    no_of_judges bigint,
    judgment_pronounced_by bigint
);


ALTER TABLE public.neutral_citation_06072023 OWNER TO postgres;

--
-- Name: neutral_citation_06072023_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.neutral_citation_06072023_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.neutral_citation_06072023_id_seq OWNER TO postgres;

--
-- Name: neutral_citation_06072023_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.neutral_citation_06072023_id_seq OWNED BY public.neutral_citation_06072023.id;


--
-- Name: neutral_citation_24042023; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.neutral_citation_24042023 (
    id bigint NOT NULL,
    diary_no bigint,
    nc_number bigint,
    nc_year bigint,
    nc_display character varying(45),
    updated_by bigint,
    updated_on timestamp with time zone,
    is_deleted character varying(2) DEFAULT 'f'::character varying,
    active_casetype_id bigint,
    active_fil_no character varying(45),
    active_reg_year bigint,
    pet_name character varying(700),
    res_name character varying(700),
    dispose_order_date date,
    reg_no_display text,
    order_type character varying(5),
    coram character varying(100),
    no_of_judges bigint
);


ALTER TABLE public.neutral_citation_24042023 OWNER TO postgres;

--
-- Name: neutral_citation_24042023_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.neutral_citation_24042023_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.neutral_citation_24042023_id_seq OWNER TO postgres;

--
-- Name: neutral_citation_24042023_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.neutral_citation_24042023_id_seq OWNED BY public.neutral_citation_24042023.id;


--
-- Name: neutral_citation_deleted; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.neutral_citation_deleted (
    id bigint NOT NULL,
    diary_no bigint,
    nc_number bigint,
    nc_year bigint,
    nc_display character varying(45),
    updated_by bigint,
    updated_on timestamp with time zone,
    is_deleted character varying(2) DEFAULT 'f'::character varying,
    active_casetype_id bigint,
    active_fil_no character varying(45),
    active_reg_year bigint,
    pet_name character varying(700),
    res_name character varying(700),
    dispose_order_date date,
    reg_no_display text,
    order_type character varying(5),
    coram character varying(100),
    no_of_judges bigint,
    judgment_pronounced_by bigint,
    deleted_on date,
    deleted_by character varying(45),
    reason_for_deletion character varying(500),
    deleted_by_ip character varying(25),
    is_used character varying(15)
);


ALTER TABLE public.neutral_citation_deleted OWNER TO postgres;

--
-- Name: neutral_citation_deleted_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.neutral_citation_deleted_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.neutral_citation_deleted_id_seq OWNER TO postgres;

--
-- Name: neutral_citation_deleted_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.neutral_citation_deleted_id_seq OWNED BY public.neutral_citation_deleted.id;


--
-- Name: neutral_citation_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.neutral_citation_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.neutral_citation_id_seq OWNER TO postgres;

--
-- Name: neutral_citation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.neutral_citation_id_seq OWNED BY public.neutral_citation.id;


--
-- Name: new_subject_category_updation; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.new_subject_category_updation (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    submaster_id bigint,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_by_user_type bigint,
    display character varying(5),
    remarks character varying(500),
    is_red_flag character varying(2)
);


ALTER TABLE public.new_subject_category_updation OWNER TO postgres;

--
-- Name: new_subject_category_updation_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.new_subject_category_updation_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.new_subject_category_updation_id_seq OWNER TO postgres;

--
-- Name: new_subject_category_updation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.new_subject_category_updation_id_seq OWNED BY public.new_subject_category_updation.id;


--
-- Name: nic_cloud_tbfh; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.nic_cloud_tbfh (
    sno2 double precision,
    sno double precision,
    last_head bytea,
    actual_head bigint DEFAULT '0'::bigint NOT NULL,
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    submaster_id bigint NOT NULL,
    sub_name1 character varying(250),
    sub_name2 character varying(250),
    sub_name3 character varying(250),
    sub_name4 character varying(250),
    subcode1 bigint DEFAULT 0,
    subcode2 bigint DEFAULT 0,
    subcode3 bigint DEFAULT '0'::bigint,
    subcode4 bigint DEFAULT '0'::bigint,
    diary_no_rec_date timestamp with time zone,
    n_dt character varying(10),
    next_dt character varying(10),
    clno bigint DEFAULT 0,
    brd_slno bigint DEFAULT 0 NOT NULL,
    conn_key character varying(10),
    mondayofweek date
);


ALTER TABLE public.nic_cloud_tbfh OWNER TO postgres;

--
-- Name: njdg_act; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.njdg_act (
    diary_no bigint NOT NULL,
    nc_act_code_1 bigint,
    acts character varying(100),
    nc_act_name_1 character varying(100),
    section_1 character varying(45),
    create_modify timestamp with time zone
);


ALTER TABLE public.njdg_act OWNER TO postgres;

--
-- Name: njdg_category_transaction; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.njdg_category_transaction (
    cino character varying(16) NOT NULL,
    submaster_id bigint,
    category_id bigint,
    category_name character varying(200),
    sub_category_id bigint,
    sub_category_name character varying(200),
    insert_date_time timestamp with time zone,
    create_modify timestamp with time zone,
    entry_source_flag bigint DEFAULT '1'::bigint NOT NULL
);


ALTER TABLE public.njdg_category_transaction OWNER TO postgres;

--
-- Name: COLUMN njdg_category_transaction.entry_source_flag; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.njdg_category_transaction.entry_source_flag IS 'insert-1,update-2,delete-0';


--
-- Name: njdg_cino; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.njdg_cino (
    id bigint NOT NULL,
    cnr_running bigint,
    cnr_year bigint
);


ALTER TABLE public.njdg_cino OWNER TO postgres;

--
-- Name: njdg_cino_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.njdg_cino_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.njdg_cino_id_seq OWNER TO postgres;

--
-- Name: njdg_cino_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.njdg_cino_id_seq OWNED BY public.njdg_cino.id;


--
-- Name: njdg_lower_court; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.njdg_lower_court (
    cino character varying(16),
    diary_no bigint,
    court_type_code character varying(50),
    court_type_name character varying(50),
    court_state_code character varying(50),
    court_state_name character varying(50),
    court_name character varying(100),
    case_type_code bigint,
    case_type_name character varying(100),
    case_number bigint,
    case_year bigint,
    order_date date,
    is_judgement_challenged smallint DEFAULT '0'::smallint NOT NULL,
    is_active smallint DEFAULT '1'::smallint NOT NULL,
    create_modify timestamp with time zone
);


ALTER TABLE public.njdg_lower_court OWNER TO postgres;

--
-- Name: njdg_ordernet; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.njdg_ordernet (
    diary_no bigint,
    url_flag character varying(20),
    cino character varying(20),
    file_name character varying(50),
    is_active smallint DEFAULT '1'::smallint NOT NULL,
    create_modify timestamp with time zone
);


ALTER TABLE public.njdg_ordernet OWNER TO postgres;

--
-- Name: njdg_ordernet_16102022; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.njdg_ordernet_16102022 (
    diary_no bigint,
    url_flag character varying(20),
    cino character varying(20),
    file_name character varying(50),
    is_active smallint DEFAULT '1'::smallint NOT NULL,
    create_modify timestamp with time zone
);


ALTER TABLE public.njdg_ordernet_16102022 OWNER TO postgres;

--
-- Name: njdg_purpose; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.njdg_purpose (
    purpose_code bigint NOT NULL,
    purpose_name character varying(45),
    national_code bigint,
    short_name character varying(45)
);


ALTER TABLE public.njdg_purpose OWNER TO postgres;

--
-- Name: njdg_stats; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.njdg_stats (
    id bigint NOT NULL,
    source text NOT NULL,
    main_flag character varying(150),
    flag text NOT NULL,
    total_count bigint DEFAULT '0'::bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    created_by_ip character varying(50),
    created_date timestamp with time zone,
    active_casetype_id bigint,
    year bigint
);


ALTER TABLE public.njdg_stats OWNER TO postgres;

--
-- Name: njdg_stats_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.njdg_stats_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.njdg_stats_id_seq OWNER TO postgres;

--
-- Name: njdg_stats_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.njdg_stats_id_seq OWNED BY public.njdg_stats.id;


--
-- Name: njdg_transaction; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.njdg_transaction (
    cino character varying(16) NOT NULL,
    filcase_type bigint,
    fil_case_type_nc_full_name character varying(100),
    fil_case_type_nc bigint,
    fil_case_type_nc_name character varying(45),
    fil_case_type_nc_type character varying(45),
    fil_case_type_name_in_est character varying(45),
    fil_no bigint,
    fil_year bigint,
    filing_no bigint,
    regcase_type_full_name character varying(45),
    regcase_type_nc bigint,
    regcase_type_nc_name character varying(45),
    regcase_type_nc_full_name character varying(45),
    regcase_type_nc_type character varying(45),
    regcase_type_name_in_est character varying(45),
    reg_no character varying(45),
    reg_year bigint,
    regcase_type character varying(45),
    case_no character varying(45),
    main_case_no character varying(45),
    main_matter_cino character varying(16),
    maincase_filing_no character varying(45),
    pet_name character varying(45),
    pet_gender character(1),
    pet_dob date,
    pet_age bigint,
    pet_sex bigint,
    pet_adv character varying(45),
    pet_adv_bar_regn character varying(45),
    hide_pet_name character(1),
    res_name character varying(45),
    res_dob date,
    res_age bigint,
    res_sex bigint,
    res_gender character(1),
    hide_res_name character(1),
    res_adv character varying(45),
    res_bar_regn character varying(45),
    purpose_today character varying(45),
    purpose_today_nc character varying(45),
    purpose_today_nc_type character varying(45),
    purpose_today_nc_name character varying(45),
    purpose_today_name_in_est character varying(45),
    purpose_prev character varying(45),
    purpose_prev_nc character varying(45),
    purpose_prev_nc_type character varying(45),
    purpose_prev_nc_name character varying(45),
    purpose_prev_name_in_est character varying(45),
    purpose_next character varying(45),
    purpose_next_nc bigint,
    purpose_next_nc_type character varying(45),
    purpose_next_nc_name character varying(45),
    disp_nature character varying(45),
    disp_nature_o character varying(45),
    disp_nature_nc bigint,
    disp_nature_nc_group character varying(45),
    disp_nature_nc_name character varying(45),
    disp_nature_name_in_est character varying(45),
    est_code character varying(45),
    est_name character varying(45),
    case_info_time_stamp timestamp with time zone,
    date_of_filing date,
    dt_regis date,
    date_filing_disp date,
    date_first_list date,
    date_last_list date,
    date_next_list date,
    date_of_decision date,
    date_of_decision_o date,
    court_no bigint,
    judge_name_all character varying(700),
    judge_name_in_est character varying(700),
    jocode character varying(150),
    create_modify timestamp with time zone,
    disposal_year bigint,
    hide_partyname character(1),
    ci_cri character(1),
    diary_no bigint NOT NULL,
    cino_conversion character varying(16),
    main_casetype_history_id bigint,
    insert_date_time timestamp with time zone,
    entry_source_flag bigint DEFAULT '1'::bigint NOT NULL,
    jocode_count bigint DEFAULT '0'::bigint NOT NULL,
    category_id bigint,
    category_name character varying(200),
    sub_category_id bigint,
    sub_category_name character varying(200),
    mainhead character(1),
    main_supp_flag bigint,
    next_dt date,
    board_type character(1),
    from_cino_conversion character varying(16),
    to_cino_conversion character varying(16),
    icmis_registration_no character varying(16)
);


ALTER TABLE public.njdg_transaction OWNER TO postgres;

--
-- Name: COLUMN njdg_transaction.entry_source_flag; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.njdg_transaction.entry_source_flag IS 'insert-1,update-2,delete-0';


--
-- Name: COLUMN njdg_transaction.mainhead; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.njdg_transaction.mainhead IS 'M - misc, F - Regular';


--
-- Name: COLUMN njdg_transaction.main_supp_flag; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.njdg_transaction.main_supp_flag IS '0 - ready, 1 - listed main, 2 - listed suppli, 3 - not ready';


--
-- Name: njdg_transaction_bck_11102022; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.njdg_transaction_bck_11102022 (
    cino character varying(16) NOT NULL,
    filcase_type bigint,
    fil_case_type_nc_full_name character varying(100),
    fil_case_type_nc bigint,
    fil_case_type_nc_name character varying(45),
    fil_case_type_nc_type character varying(45),
    fil_case_type_name_in_est character varying(45),
    fil_no bigint,
    fil_year bigint,
    filing_no bigint,
    regcase_type_full_name character varying(45),
    regcase_type_nc bigint,
    regcase_type_nc_name character varying(45),
    regcase_type_nc_full_name character varying(45),
    regcase_type_nc_type character varying(45),
    regcase_type_name_in_est character varying(45),
    reg_no character varying(45),
    reg_year bigint,
    regcase_type character varying(45),
    case_no character varying(45),
    main_case_no character varying(45),
    main_matter_cino character varying(16),
    maincase_filing_no character varying(45),
    pet_name character varying(45),
    pet_gender character(1),
    pet_dob date,
    pet_age bigint,
    pet_sex bigint,
    pet_adv character varying(45),
    pet_adv_bar_regn character varying(45),
    hide_pet_name character(1),
    res_name character varying(45),
    res_dob date,
    res_age bigint,
    res_sex bigint,
    res_gender character(1),
    hide_res_name character(1),
    res_adv character varying(45),
    res_bar_regn character varying(45),
    purpose_today character varying(45),
    purpose_today_nc character varying(45),
    purpose_today_nc_type character varying(45),
    purpose_today_nc_name character varying(45),
    purpose_today_name_in_est character varying(45),
    purpose_prev character varying(45),
    purpose_prev_nc character varying(45),
    purpose_prev_nc_type character varying(45),
    purpose_prev_nc_name character varying(45),
    purpose_prev_name_in_est character varying(45),
    purpose_next character varying(45),
    purpose_next_nc bigint,
    purpose_next_nc_type character varying(45),
    purpose_next_nc_name character varying(45),
    disp_nature character varying(45),
    disp_nature_o character varying(45),
    disp_nature_nc bigint,
    disp_nature_nc_group character varying(45),
    disp_nature_nc_name character varying(45),
    disp_nature_name_in_est character varying(45),
    est_code character varying(45),
    est_name character varying(45),
    case_info_time_stamp timestamp with time zone,
    date_of_filing date,
    dt_regis date,
    date_filing_disp date,
    date_first_list date,
    date_last_list date,
    date_next_list date,
    date_of_decision date,
    date_of_decision_o date,
    court_no bigint,
    judge_name_all character varying(700),
    judge_name_in_est character varying(700),
    jocode character varying(150),
    create_modify timestamp with time zone,
    disposal_year bigint,
    hide_partyname character(1),
    ci_cri character(1),
    diary_no bigint NOT NULL,
    cino_conversion character varying(16),
    main_casetype_history_id bigint,
    insert_date_time timestamp with time zone,
    entry_source_flag bigint DEFAULT '1'::bigint NOT NULL
);


ALTER TABLE public.njdg_transaction_bck_11102022 OWNER TO postgres;

--
-- Name: COLUMN njdg_transaction_bck_11102022.entry_source_flag; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.njdg_transaction_bck_11102022.entry_source_flag IS 'insert-1,update-2,delete-0';


--
-- Name: njrs_mails; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.njrs_mails (
    id bigint NOT NULL,
    to_sender character varying(500),
    from_date date,
    to_date date,
    subject character varying(200),
    display character varying(45),
    usercode bigint,
    created_on character varying(45)
);


ALTER TABLE public.njrs_mails OWNER TO postgres;

--
-- Name: njrs_mails_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.njrs_mails_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.njrs_mails_id_seq OWNER TO postgres;

--
-- Name: njrs_mails_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.njrs_mails_id_seq OWNED BY public.njrs_mails.id;


--
-- Name: not_before; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.not_before (
    id bigint NOT NULL,
    diary_no character varying(50) NOT NULL,
    j1 bigint DEFAULT '0'::bigint NOT NULL,
    notbef character(1) NOT NULL,
    usercode bigint DEFAULT '0'::bigint NOT NULL,
    ent_dt timestamp with time zone,
    u_ip character varying(15) NOT NULL,
    u_mac character varying(20) NOT NULL,
    enterby bigint,
    res_add character varying(50) NOT NULL,
    res_id bigint NOT NULL,
    create_modify timestamp without time zone,
    updated_by bigint,
    updated_by_ip text
);


ALTER TABLE public.not_before OWNER TO postgres;

--
-- Name: not_before_his; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.not_before_his (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    j1 bigint DEFAULT '0'::bigint,
    notbef character(1) NOT NULL,
    usercode bigint DEFAULT '0'::bigint NOT NULL,
    ent_dt timestamp with time zone,
    old_u_ip character varying(15) NOT NULL,
    old_u_mac character varying(20) NOT NULL,
    cur_u_ip character varying(15) NOT NULL,
    cur_u_mac character varying(20) NOT NULL,
    cur_ucode bigint NOT NULL,
    c_dt timestamp with time zone,
    enterby_old bigint,
    action character varying(50),
    old_res_add character varying(50),
    old_res_id bigint,
    del_reason character varying(30)
);


ALTER TABLE public.not_before_his OWNER TO postgres;

--
-- Name: not_before_his_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.not_before_his_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.not_before_his_id_seq OWNER TO postgres;

--
-- Name: not_before_his_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.not_before_his_id_seq OWNED BY public.not_before_his.id;


--
-- Name: not_before_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.not_before_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.not_before_id_seq OWNER TO postgres;

--
-- Name: not_before_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.not_before_id_seq OWNED BY public.not_before.id;


--
-- Name: obj_save; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.obj_save (
    id bigint NOT NULL,
    diary_no bigint,
    org_id bigint NOT NULL,
    org_id_old bigint,
    save_dt timestamp with time zone,
    rm_dt timestamp with time zone,
    status bigint DEFAULT 0 NOT NULL,
    j1_date timestamp with time zone,
    j1_sn_dt timestamp with time zone,
    j1_tot_da timestamp with time zone,
    usercode bigint NOT NULL,
    remark character varying(500) NOT NULL,
    mul_ent character varying(500) NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    rm_user_id bigint,
    rm_on_back_date timestamp with time zone,
    refil_cancel_user bigint,
    refil_cancel_date timestamp with time zone,
    create_modify timestamp with time zone,
    updated_on timestamp with time zone,
    updated_by bigint,
    updated_by_ip character varying
);


ALTER TABLE public.obj_save OWNER TO postgres;

--
-- Name: obj_save_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.obj_save_a (
    id bigint,
    diary_no bigint,
    org_id bigint,
    org_id_old bigint,
    save_dt timestamp with time zone,
    rm_dt timestamp with time zone,
    status bigint,
    j1_date timestamp with time zone,
    j1_sn_dt timestamp with time zone,
    j1_tot_da timestamp with time zone,
    usercode bigint,
    remark character varying(500),
    mul_ent character varying(500),
    display character varying(1),
    rm_user_id bigint,
    rm_on_back_date timestamp with time zone,
    refil_cancel_user bigint,
    refil_cancel_date timestamp with time zone
);


ALTER TABLE public.obj_save_a OWNER TO dev;

--
-- Name: obj_save_his; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.obj_save_his (
    id bigint NOT NULL,
    obj_id bigint NOT NULL,
    j1_date_his timestamp with time zone,
    j1_sn_dt_his timestamp with time zone,
    j1_tot_da_his timestamp with time zone
);


ALTER TABLE public.obj_save_his OWNER TO postgres;

--
-- Name: obj_save_his_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.obj_save_his_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.obj_save_his_id_seq OWNER TO postgres;

--
-- Name: obj_save_his_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.obj_save_his_id_seq OWNED BY public.obj_save_his.id;


--
-- Name: obj_save_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.obj_save_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.obj_save_id_seq OWNER TO postgres;

--
-- Name: obj_save_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.obj_save_id_seq OWNED BY public.obj_save.id;


--
-- Name: objrem; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.objrem (
    diary_no bigint,
    remark text
);


ALTER TABLE public.objrem OWNER TO postgres;

--
-- Name: objrem_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.objrem_a (
    diary_no bigint,
    remark text
);


ALTER TABLE public.objrem_a OWNER TO dev;

--
-- Name: office_report_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.office_report_details (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    order_dt date,
    office_report_id bigint NOT NULL,
    rec_dt timestamp with time zone,
    rec_user_id bigint NOT NULL,
    status character varying(1) NOT NULL,
    office_repot_name character varying(100) NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    web_status bigint NOT NULL,
    master_id bigint NOT NULL,
    batch bigint NOT NULL,
    discarded_by bigint,
    user_ip character varying(45),
    discarded_date timestamp with time zone,
    summary character varying(500),
    gist_last_read_datetime timestamp with time zone
);


ALTER TABLE public.office_report_details OWNER TO postgres;

--
-- Name: office_report_details_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.office_report_details_a (
    id bigint,
    diary_no bigint,
    order_dt date,
    office_report_id bigint,
    rec_dt timestamp with time zone,
    rec_user_id bigint,
    status character varying(1),
    office_repot_name character varying(100),
    display character varying(1),
    web_status bigint,
    master_id bigint,
    batch bigint,
    discarded_by bigint,
    user_ip character varying(45),
    discarded_date timestamp with time zone,
    summary character varying(500),
    gist_last_read_datetime timestamp with time zone
);


ALTER TABLE public.office_report_details_a OWNER TO dev;

--
-- Name: office_report_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.office_report_details_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.office_report_details_id_seq OWNER TO postgres;

--
-- Name: office_report_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.office_report_details_id_seq OWNED BY public.office_report_details.id;


--
-- Name: or_gist; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.or_gist (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    list_dt date,
    gist_remark text NOT NULL,
    usercode bigint NOT NULL,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    deleted_by bigint,
    deleted_on timestamp with time zone
);


ALTER TABLE public.or_gist OWNER TO postgres;

--
-- Name: or_gist_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.or_gist_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.or_gist_id_seq OWNER TO postgres;

--
-- Name: or_gist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.or_gist_id_seq OWNED BY public.or_gist.id;


--
-- Name: order_type_changed_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.order_type_changed_log (
    id bigint NOT NULL,
    tbl_name character varying(45),
    tbl_id bigint,
    user_id bigint,
    ent_dt timestamp with time zone,
    order_type character varying(45),
    modified_by bigint,
    modified_date timestamp with time zone
);


ALTER TABLE public.order_type_changed_log OWNER TO postgres;

--
-- Name: order_type_changed_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.order_type_changed_log_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.order_type_changed_log_id_seq OWNER TO postgres;

--
-- Name: order_type_changed_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.order_type_changed_log_id_seq OWNED BY public.order_type_changed_log.id;


--
-- Name: ordernet; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ordernet (
    id bigint NOT NULL,
    diary_no bigint DEFAULT '0'::bigint,
    petn character varying(100),
    resp character varying(100),
    petadv character varying(50),
    resadv character varying(50),
    perj bigint DEFAULT '0'::bigint NOT NULL,
    orderdate date,
    old_pdfname character varying(200),
    pdfname character varying(200),
    upload character(1),
    usercode bigint DEFAULT '0'::bigint NOT NULL,
    ent_dt timestamp with time zone,
    type character varying(2) NOT NULL,
    h_p character varying(1) NOT NULL,
    afr character varying(1) DEFAULT 'N'::character varying NOT NULL,
    prnt_name character varying(200) NOT NULL,
    prnt_dt timestamp with time zone,
    subject character varying(200) NOT NULL,
    web_status bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    roster_id bigint NOT NULL,
    pdf_generated_name character varying(200) NOT NULL,
    pdf_generated_date timestamp with time zone,
    c_type bigint NOT NULL,
    c_num character varying(20) NOT NULL,
    c_year character varying(4) NOT NULL,
    ordertextdata text,
    pdf_hash_value character varying(250),
    create_modify timestamp with time zone,
    pdf_hash_value_date_time character varying(250)
);


ALTER TABLE public.ordernet OWNER TO postgres;

--
-- Name: ordernet_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.ordernet_a (
    id bigint,
    diary_no bigint,
    petn character varying(100),
    resp character varying(100),
    petadv character varying(50),
    resadv character varying(50),
    perj bigint,
    orderdate date,
    old_pdfname character varying(200),
    pdfname character varying(200),
    upload character(1),
    usercode bigint,
    ent_dt timestamp with time zone,
    type character varying(2),
    h_p character varying(1),
    afr character varying(1),
    prnt_name character varying(200),
    prnt_dt timestamp with time zone,
    subject character varying(200),
    web_status bigint,
    display character varying(1),
    roster_id bigint,
    pdf_generated_name character varying(200),
    pdf_generated_date timestamp with time zone,
    c_type bigint,
    c_num character varying(20),
    c_year character varying(4),
    ordertextdata text,
    pdf_hash_value character varying(250),
    create_modify timestamp with time zone,
    pdf_hash_value_date_time character varying(250)
);


ALTER TABLE public.ordernet_a OWNER TO dev;

--
-- Name: ordernet_deleted; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ordernet_deleted (
    id bigint NOT NULL,
    diary_no bigint DEFAULT '0'::bigint,
    petn character varying(100),
    resp character varying(100),
    petadv character varying(50),
    resadv character varying(50),
    perj bigint DEFAULT '0'::bigint NOT NULL,
    orderdate date,
    old_pdfname character varying(200),
    pdfname character varying(200),
    upload character(1),
    usercode bigint DEFAULT '0'::bigint NOT NULL,
    ent_dt timestamp with time zone,
    type character varying(2) NOT NULL,
    h_p character varying(1) NOT NULL,
    afr character varying(1) DEFAULT 'N'::character varying NOT NULL,
    prnt_name character varying(200) NOT NULL,
    prnt_dt timestamp with time zone,
    subject character varying(200) NOT NULL,
    web_status bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    roster_id bigint NOT NULL,
    pdf_generated_name character varying(200) NOT NULL,
    pdf_generated_date timestamp with time zone,
    c_type bigint NOT NULL,
    c_num character varying(20) NOT NULL,
    c_year character varying(4) NOT NULL,
    ordertextdata text,
    pdf_hash_value character varying(250),
    create_modify timestamp with time zone,
    pdf_hash_value_date_time character varying(250),
    deleted_on timestamp with time zone,
    deleted_reason text,
    deleted_by bigint
);


ALTER TABLE public.ordernet_deleted OWNER TO postgres;

--
-- Name: ordernet_deleted_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ordernet_deleted_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ordernet_deleted_id_seq OWNER TO postgres;

--
-- Name: ordernet_deleted_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ordernet_deleted_id_seq OWNED BY public.ordernet_deleted.id;


--
-- Name: ordernet_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ordernet_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ordernet_id_seq OWNER TO postgres;

--
-- Name: ordernet_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ordernet_id_seq OWNED BY public.ordernet.id;


--
-- Name: ordernet_org; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ordernet_org (
    id bigint NOT NULL,
    diary_no bigint DEFAULT '0'::bigint,
    petn character varying(100),
    resp character varying(100),
    petadv character varying(50),
    resadv character varying(50),
    perj bigint DEFAULT '0'::bigint NOT NULL,
    orderdate date,
    old_pdfname character varying(200),
    pdfname character varying(200),
    upload character(1),
    usercode bigint DEFAULT '0'::bigint NOT NULL,
    ent_dt timestamp with time zone,
    type character varying(2) NOT NULL,
    h_p character varying(1) NOT NULL,
    afr character varying(1) DEFAULT 'N'::character varying NOT NULL,
    prnt_name character varying(200) NOT NULL,
    prnt_dt timestamp with time zone,
    subject character varying(200) NOT NULL,
    web_status bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    roster_id bigint NOT NULL,
    pdf_generated_name character varying(200) NOT NULL,
    pdf_generated_date timestamp with time zone
);


ALTER TABLE public.ordernet_org OWNER TO postgres;

--
-- Name: ordernet_org_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ordernet_org_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ordernet_org_id_seq OWNER TO postgres;

--
-- Name: ordernet_org_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ordernet_org_id_seq OWNED BY public.ordernet_org.id;


--
-- Name: ordernet_rop_sci; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ordernet_rop_sci (
    diary_no bigint,
    diary_year bigint,
    c_type bigint NOT NULL,
    c_no character varying(200) NOT NULL,
    c_yr bigint NOT NULL,
    rop date,
    file_path character varying(1000) NOT NULL
);


ALTER TABLE public.ordernet_rop_sci OWNER TO postgres;

--
-- Name: original_records_file; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.original_records_file (
    id bigint NOT NULL,
    diary_no bigint,
    file_name character varying(200),
    usercode bigint,
    updated_on timestamp with time zone,
    display character varying(1)
);


ALTER TABLE public.original_records_file OWNER TO postgres;

--
-- Name: original_records_file_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.original_records_file_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.original_records_file_id_seq OWNER TO postgres;

--
-- Name: original_records_file_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.original_records_file_id_seq OWNED BY public.original_records_file.id;


--
-- Name: other_category; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.other_category (
    id bigint NOT NULL,
    diary_no bigint,
    submaster_id bigint,
    remarks character varying(500),
    ent_user bigint,
    ent_datetime timestamp with time zone,
    upd_user bigint,
    upd_datetime timestamp with time zone,
    display character(1)
);


ALTER TABLE public.other_category OWNER TO postgres;

--
-- Name: other_category_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.other_category_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.other_category_id_seq OWNER TO postgres;

--
-- Name: other_category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.other_category_id_seq OWNED BY public.other_category.id;


--
-- Name: otp_based_login_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.otp_based_login_history (
    id bigint NOT NULL,
    updated_by bigint NOT NULL,
    otp_send_time timestamp with time zone,
    otp_entered_time timestamp with time zone,
    otp_session_start_time timestamp with time zone,
    otp_session_logout_time timestamp with time zone,
    next_dt date,
    mainhead character(1) NOT NULL,
    board_type character(1) NOT NULL,
    main_supp_flag bigint NOT NULL,
    no_of_times_used bigint DEFAULT '0'::bigint,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL
);


ALTER TABLE public.otp_based_login_history OWNER TO postgres;

--
-- Name: otp_based_login_history_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.otp_based_login_history_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.otp_based_login_history_id_seq OWNER TO postgres;

--
-- Name: otp_based_login_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.otp_based_login_history_id_seq OWNED BY public.otp_based_login_history.id;


--
-- Name: otp_sent_detail; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.otp_sent_detail (
    id bigint NOT NULL,
    otp_based_login_history_id bigint,
    usercode bigint,
    otp_sent bigint,
    otp_sent_time timestamp with time zone,
    otp_entered bigint,
    otp_entered_time timestamp with time zone,
    no_of_times_wrong_attemt bigint DEFAULT '0'::bigint,
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE public.otp_sent_detail OWNER TO postgres;

--
-- Name: otp_sent_detail_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.otp_sent_detail_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.otp_sent_detail_id_seq OWNER TO postgres;

--
-- Name: otp_sent_detail_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.otp_sent_detail_id_seq OWNED BY public.otp_sent_detail.id;


--
-- Name: pap_book; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pap_book (
    id bigint NOT NULL,
    fil_no character varying(14) NOT NULL,
    con_fil character varying(14) NOT NULL,
    dis_dt timestamp with time zone,
    dis_id bigint NOT NULL,
    estimate_y_n bigint NOT NULL,
    pab_rec_dt timestamp with time zone,
    no_of_cps bigint NOT NULL,
    pab_rec_id bigint NOT NULL,
    adv_mn bigint NOT NULL,
    no_of_pg bigint NOT NULL,
    est_cost double precision NOT NULL,
    pb_user_id bigint NOT NULL,
    pb_rec_dt timestamp with time zone,
    est_print bigint NOT NULL,
    print bigint NOT NULL,
    def_status bigint NOT NULL,
    da_send_dt timestamp with time zone,
    or_cost double precision NOT NULL,
    org_cost_re_dt timestamp with time zone,
    org_cost_u_id bigint NOT NULL,
    phocp_rec_dt timestamp with time zone,
    phocp_us_id bigint NOT NULL,
    ready bigint DEFAULT '0'::bigint NOT NULL,
    rd_user_id bigint NOT NULL,
    rd_date timestamp with time zone,
    display character varying(1) DEFAULT 'Y'::character varying,
    dis_link bigint NOT NULL,
    supreme_rec_dt timestamp with time zone,
    supreme_disp_dt timestamp with time zone,
    pab_rec_dt_1 timestamp with time zone
);


ALTER TABLE public.pap_book OWNER TO postgres;

--
-- Name: pap_book_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pap_book_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pap_book_id_seq OWNER TO postgres;

--
-- Name: pap_book_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.pap_book_id_seq OWNED BY public.pap_book.id;


--
-- Name: paper_book_sms_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.paper_book_sms_log (
    id bigint NOT NULL,
    mobile character varying(500) NOT NULL,
    msg text NOT NULL,
    send_by bigint,
    send_date_time timestamp with time zone,
    ip_address character varying(15),
    send_status character varying(50),
    sms_for character varying(20)
);


ALTER TABLE public.paper_book_sms_log OWNER TO postgres;

--
-- Name: paper_book_sms_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.paper_book_sms_log_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.paper_book_sms_log_id_seq OWNER TO postgres;

--
-- Name: paper_book_sms_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.paper_book_sms_log_id_seq OWNED BY public.paper_book_sms_log.id;


--
-- Name: party; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.party (
    diary_no bigint NOT NULL,
    pet_res character(1) DEFAULT 'P'::bpchar NOT NULL,
    sr_no bigint NOT NULL,
    sr_no_show character varying(100) NOT NULL,
    ind_dep character(2) DEFAULT 'I'::bpchar NOT NULL,
    partysuff character varying(255),
    partyname character varying(700),
    sonof character(255),
    authcode bigint DEFAULT 0 NOT NULL,
    state_in_name bigint,
    prfhname character varying(255),
    age bigint DEFAULT 0,
    sex character(1),
    caste character varying(10),
    addr1 character varying(300),
    addr2 character varying(300),
    state character varying(155),
    city character varying(155),
    pin bigint,
    email character varying(255) DEFAULT NULL::character varying,
    usercode bigint,
    ent_dt timestamp with time zone,
    pflag character(1) DEFAULT 'P'::bpchar NOT NULL,
    dstname character varying(100),
    deptcode bigint DEFAULT 0,
    pan_card character varying(10),
    adhar_card character varying(12),
    country bigint,
    education character varying(100),
    occ_code bigint,
    edu_code bigint,
    lowercase_id bigint,
    auto_generated_id bigint NOT NULL,
    remark_lrs text,
    remark_del text,
    cont_pro_info public.party_cont_pro_info,
    last_dt timestamp with time zone,
    last_usercode character varying(255),
    hindi_timestamp timestamp with time zone,
    partyname_hindi character varying(700),
    contact character varying(100),
    create_modify timestamp without time zone,
    updated_by bigint,
    updated_by_ip text
);


ALTER TABLE public.party OWNER TO postgres;

--
-- Name: party_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.party_a (
    diary_no bigint,
    pet_res character(1),
    sr_no bigint,
    sr_no_show character varying(10),
    ind_dep character(2),
    partysuff character varying(100),
    partyname character varying(700),
    sonof character(1),
    authcode bigint,
    state_in_name bigint,
    prfhname character varying(100),
    age bigint,
    sex character(1),
    caste character varying(10),
    addr1 character varying(300),
    addr2 character varying(300),
    state character varying(15),
    city character varying(15),
    pin bigint,
    email character varying(50),
    contact bigint,
    usercode bigint,
    ent_dt timestamp with time zone,
    pflag character(1),
    dstname character varying(30),
    deptcode bigint,
    pan_card character varying(10),
    adhar_card character varying(12),
    country bigint,
    education character varying(50),
    occ_code bigint,
    edu_code bigint,
    lowercase_id bigint,
    auto_generated_id bigint,
    remark_lrs text,
    remark_del text,
    cont_pro_info public.party_cont_pro_info,
    last_dt timestamp with time zone,
    last_usercode character varying(45),
    hindi_timestamp timestamp with time zone,
    partyname_hindi character varying(700)
);


ALTER TABLE public.party_a OWNER TO dev;

--
-- Name: party_additional_address; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.party_additional_address (
    id bigint NOT NULL,
    party_id bigint NOT NULL,
    country bigint NOT NULL,
    state bigint NOT NULL,
    district bigint NOT NULL,
    address character varying(500) NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    create_modify timestamp without time zone,
    updated_by bigint,
    updated_by_ip text
);


ALTER TABLE public.party_additional_address OWNER TO postgres;

--
-- Name: party_additional_address_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.party_additional_address_a (
    id bigint,
    party_id bigint,
    country bigint,
    state bigint,
    district bigint,
    address character varying(500),
    display character varying(1)
);


ALTER TABLE public.party_additional_address_a OWNER TO dev;

--
-- Name: party_additional_address_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.party_additional_address_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.party_additional_address_id_seq OWNER TO postgres;

--
-- Name: party_additional_address_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.party_additional_address_id_seq OWNED BY public.party_additional_address.id;


--
-- Name: party_auto_generated_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.party_auto_generated_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.party_auto_generated_id_seq OWNER TO postgres;

--
-- Name: party_auto_generated_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.party_auto_generated_id_seq OWNED BY public.party.auto_generated_id;


--
-- Name: party_autocomp; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.party_autocomp (
    party character varying(200),
    fh character varying(200),
    dst character varying(50),
    addr character varying(500)
);


ALTER TABLE public.party_autocomp OWNER TO postgres;

--
-- Name: party_lowercourt; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.party_lowercourt (
    id bigint NOT NULL,
    party_id bigint,
    lowercase_id bigint,
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE public.party_lowercourt OWNER TO postgres;

--
-- Name: party_lowercourt_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.party_lowercourt_a (
    id bigint,
    party_id bigint,
    lowercase_id bigint,
    display character(1)
);


ALTER TABLE public.party_lowercourt_a OWNER TO dev;

--
-- Name: party_lowercourt_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.party_lowercourt_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.party_lowercourt_id_seq OWNER TO postgres;

--
-- Name: party_lowercourt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.party_lowercourt_id_seq OWNED BY public.party_lowercourt.id;


--
-- Name: party_order; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.party_order (
    id bigint NOT NULL,
    "user" bigint NOT NULL,
    o1 character(1) NOT NULL,
    o2 character(1) NOT NULL,
    o3 character(1) NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    ent_dt timestamp with time zone
);


ALTER TABLE public.party_order OWNER TO postgres;

--
-- Name: party_order_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.party_order_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.party_order_id_seq OWNER TO postgres;

--
-- Name: party_order_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.party_order_id_seq OWNED BY public.party_order.id;


--
-- Name: pendency_report; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pendency_report (
    id bigint NOT NULL,
    submaster_id bigint NOT NULL,
    main bigint NOT NULL,
    conn bigint NOT NULL,
    misc_main bigint NOT NULL,
    misc_conn bigint NOT NULL,
    regular_main bigint NOT NULL,
    regular_conn bigint NOT NULL,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    include_defect character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.pendency_report OWNER TO postgres;

--
-- Name: pendency_report_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.pendency_report_a (
    id bigint,
    submaster_id bigint,
    main bigint,
    conn bigint,
    misc_main bigint,
    misc_conn bigint,
    regular_main bigint,
    regular_conn bigint,
    ent_dt timestamp with time zone,
    display character(1),
    include_defect character(1)
);


ALTER TABLE public.pendency_report_a OWNER TO dev;

--
-- Name: pendency_report_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pendency_report_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pendency_report_id_seq OWNER TO postgres;

--
-- Name: pendency_report_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.pendency_report_id_seq OWNED BY public.pendency_report.id;


--
-- Name: pending_cases_section_id; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pending_cases_section_id (
    diary_no bigint,
    section_id bigint
);


ALTER TABLE public.pending_cases_section_id OWNER TO postgres;

--
-- Name: physical_hearing_advocate_consent; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.physical_hearing_advocate_consent (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character(1) DEFAULT 'N'::bpchar,
    next_dt date,
    is_deleted character(1) DEFAULT 'f'::bpchar NOT NULL,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15),
    vacation_list_year bigint,
    mainhead character(1) DEFAULT 'M'::bpchar NOT NULL,
    consent character(1) DEFAULT 'N'::bpchar NOT NULL,
    bar_id bigint NOT NULL
);


ALTER TABLE public.physical_hearing_advocate_consent OWNER TO postgres;

--
-- Name: physical_hearing_advocate_consent_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.physical_hearing_advocate_consent_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.physical_hearing_advocate_consent_id_seq OWNER TO postgres;

--
-- Name: physical_hearing_advocate_consent_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.physical_hearing_advocate_consent_id_seq OWNED BY public.physical_hearing_advocate_consent.id;


--
-- Name: physical_hearing_advocate_consent_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.physical_hearing_advocate_consent_log (
    id bigint DEFAULT '0'::bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character(1) DEFAULT 'N'::bpchar,
    next_dt date,
    is_deleted character(1) DEFAULT 'f'::bpchar NOT NULL,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15),
    vacation_list_year bigint,
    mainhead character(1) DEFAULT 'M'::bpchar NOT NULL,
    consent character(1) DEFAULT 'N'::bpchar NOT NULL,
    bar_id bigint NOT NULL
);


ALTER TABLE public.physical_hearing_advocate_consent_log OWNER TO postgres;

--
-- Name: physical_hearing_consent_required; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.physical_hearing_consent_required (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character(1) DEFAULT 'N'::bpchar,
    next_dt date,
    is_deleted character(1) DEFAULT 'f'::bpchar NOT NULL,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15),
    vacation_list_year bigint,
    mainhead character(1) DEFAULT 'M'::bpchar NOT NULL,
    consent character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.physical_hearing_consent_required OWNER TO postgres;

--
-- Name: physical_hearing_consent_required_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.physical_hearing_consent_required_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.physical_hearing_consent_required_id_seq OWNER TO postgres;

--
-- Name: physical_hearing_consent_required_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.physical_hearing_consent_required_id_seq OWNED BY public.physical_hearing_consent_required.id;


--
-- Name: physical_hearing_consent_required_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.physical_hearing_consent_required_log (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character(1) DEFAULT 'N'::bpchar,
    next_dt date,
    is_deleted character(1) DEFAULT 'f'::bpchar NOT NULL,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15),
    vacation_list_year bigint,
    mainhead character(1) DEFAULT 'M'::bpchar NOT NULL,
    consent character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.physical_hearing_consent_required_log OWNER TO postgres;

--
-- Name: physical_hearing_consent_required_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.physical_hearing_consent_required_log_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.physical_hearing_consent_required_log_id_seq OWNER TO postgres;

--
-- Name: physical_hearing_consent_required_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.physical_hearing_consent_required_log_id_seq OWNED BY public.physical_hearing_consent_required_log.id;


--
-- Name: physical_verify; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.physical_verify (
    diary_no bigint NOT NULL,
    ucode bigint,
    ent_dt timestamp with time zone,
    avaliable_flag character varying(2),
    display character varying(2),
    ip_address character varying(20)
);


ALTER TABLE public.physical_verify OWNER TO postgres;

--
-- Name: physical_verify_old; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.physical_verify_old (
    diary_no bigint NOT NULL,
    ucode bigint,
    ent_dt timestamp with time zone
);


ALTER TABLE public.physical_verify_old OWNER TO postgres;

--
-- Name: post_bar_code_mapping; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.post_bar_code_mapping (
    id bigint NOT NULL,
    barcode character varying(15) NOT NULL,
    copying_application_id bigint NOT NULL,
    ent_time timestamp with time zone,
    module_flag character varying(45),
    is_consumed character(1) DEFAULT '0'::bpchar NOT NULL,
    consumed_by bigint,
    consumed_on timestamp with time zone,
    is_deleted character(1) DEFAULT '0'::bpchar NOT NULL,
    deleted_by bigint,
    deleted_on timestamp with time zone,
    envelope_weight bigint NOT NULL,
    sms_sent_time timestamp with time zone,
    email_sent_time timestamp with time zone
);


ALTER TABLE public.post_bar_code_mapping OWNER TO postgres;

--
-- Name: post_bar_code_mapping_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.post_bar_code_mapping_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.post_bar_code_mapping_id_seq OWNER TO postgres;

--
-- Name: post_bar_code_mapping_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.post_bar_code_mapping_id_seq OWNED BY public.post_bar_code_mapping.id;


--
-- Name: post_envelope_movement; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.post_envelope_movement (
    id bigint NOT NULL,
    barcode character varying(15) NOT NULL,
    received_section bigint NOT NULL,
    received_by bigint NOT NULL,
    received_on timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    deleted_on timestamp with time zone,
    deleted_by bigint
);


ALTER TABLE public.post_envelope_movement OWNER TO postgres;

--
-- Name: post_envelope_movement_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.post_envelope_movement_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.post_envelope_movement_id_seq OWNER TO postgres;

--
-- Name: post_envelope_movement_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.post_envelope_movement_id_seq OWNED BY public.post_envelope_movement.id;


--
-- Name: proceedings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.proceedings (
    id bigint NOT NULL,
    order_date timestamp with time zone,
    court_number bigint,
    item_number bigint,
    diary_no bigint,
    order_details text,
    generated_by character varying(200),
    generated_on timestamp with time zone,
    file_name character varying(100),
    upload_flag boolean DEFAULT false,
    uploaded_by bigint,
    upload_date_time timestamp with time zone,
    order_type character varying(5),
    is_oral_mentioning boolean,
    replace_reason character varying(100),
    app_no character varying(1000),
    registration_number character varying(16),
    registration_year bigint,
    roster_id bigint,
    display character varying(1) DEFAULT 'Y'::character varying,
    ordernet_id bigint NOT NULL,
    is_reportable boolean DEFAULT false
);


ALTER TABLE public.proceedings OWNER TO postgres;

--
-- Name: proceedings_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.proceedings_a (
    id bigint,
    order_date timestamp with time zone,
    court_number bigint,
    item_number bigint,
    diary_no bigint,
    order_details text,
    generated_by character varying(200),
    generated_on timestamp with time zone,
    file_name character varying(100),
    upload_flag boolean,
    uploaded_by bigint,
    upload_date_time timestamp with time zone,
    order_type character varying(5),
    is_oral_mentioning boolean,
    replace_reason character varying(100),
    app_no character varying(1000),
    registration_number character varying(16),
    registration_year bigint,
    roster_id bigint,
    display character varying(1),
    ordernet_id bigint,
    is_reportable boolean
);


ALTER TABLE public.proceedings_a OWNER TO dev;

--
-- Name: proceedings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.proceedings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.proceedings_id_seq OWNER TO postgres;

--
-- Name: proceedings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.proceedings_id_seq OWNED BY public.proceedings.id;


--
-- Name: recalled_deleted; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recalled_deleted (
    diary_no bigint NOT NULL,
    ord_dt date,
    disp_dt date,
    disp_type bigint,
    updated_on timestamp with time zone,
    updation_reason character varying(255) NOT NULL,
    updated_by bigint NOT NULL,
    updated_by_ip character varying(45),
    court_or_user character varying(45)
);


ALTER TABLE public.recalled_deleted OWNER TO postgres;

--
-- Name: recalled_matters; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recalled_matters (
    diary_no bigint NOT NULL,
    ord_dt date,
    disp_dt date,
    disp_type bigint,
    updated_on timestamp with time zone,
    updation_reason character varying(255) NOT NULL,
    updated_by bigint NOT NULL,
    updated_by_ip character varying(45),
    court_or_user character varying(45)
);


ALTER TABLE public.recalled_matters OWNER TO postgres;

--
-- Name: recalled_matters_21122018; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recalled_matters_21122018 (
    diary_no bigint NOT NULL,
    ord_dt date,
    disp_dt date,
    disp_type bigint,
    transferred_on timestamp with time zone,
    transferred_reason character varying(255) NOT NULL
);


ALTER TABLE public.recalled_matters_21122018 OWNER TO postgres;

--
-- Name: record_keeping; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.record_keeping (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    consignment_status character(1) NOT NULL,
    consignment_remarks character varying(1000) DEFAULT 'null'::character varying,
    consignment_date date,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15) NOT NULL
);


ALTER TABLE public.record_keeping OWNER TO postgres;

--
-- Name: record_keeping_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.record_keeping_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.record_keeping_id_seq OWNER TO postgres;

--
-- Name: record_keeping_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.record_keeping_id_seq OWNED BY public.record_keeping.id;


--
-- Name: record_room_mails; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.record_room_mails (
    id bigint NOT NULL,
    to_sender character varying(500),
    subject character varying(200),
    display character varying(45),
    usercode bigint,
    created_on character varying(45)
);


ALTER TABLE public.record_room_mails OWNER TO postgres;

--
-- Name: record_room_mails_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.record_room_mails_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.record_room_mails_id_seq OWNER TO postgres;

--
-- Name: record_room_mails_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.record_room_mails_id_seq OWNED BY public.record_room_mails.id;


--
-- Name: ref_keyword; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ref_keyword (
    id bigint NOT NULL,
    keyword_code bigint NOT NULL,
    keyword_description character varying(500) NOT NULL,
    updated_by bigint NOT NULL,
    updated_on timestamp with time zone,
    is_deleted character varying(1) NOT NULL
);


ALTER TABLE public.ref_keyword OWNER TO postgres;

--
-- Name: ref_keyword_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ref_keyword_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ref_keyword_id_seq OWNER TO postgres;

--
-- Name: ref_keyword_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ref_keyword_id_seq OWNED BY public.ref_keyword.id;


--
-- Name: refiled_old_efiling_case_efiled_docs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.refiled_old_efiling_case_efiled_docs (
    id bigint NOT NULL,
    efiling_no character varying(45),
    efiled_type character varying(45),
    diary_no bigint,
    allocated_to bigint,
    created_at timestamp with time zone,
    created_by bigint,
    updated_by_ip character varying(30),
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE public.refiled_old_efiling_case_efiled_docs OWNER TO postgres;

--
-- Name: refiled_old_efiling_case_efiled_docs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.refiled_old_efiling_case_efiled_docs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.refiled_old_efiling_case_efiled_docs_id_seq OWNER TO postgres;

--
-- Name: refiled_old_efiling_case_efiled_docs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.refiled_old_efiling_case_efiled_docs_id_seq OWNED BY public.refiled_old_efiling_case_efiled_docs.id;


--
-- Name: reg_dt0; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.reg_dt0 (
    diary_no bigint NOT NULL,
    fil_dt character varying(45),
    fil_year bigint
);


ALTER TABLE public.reg_dt0 OWNER TO postgres;

--
-- Name: registered_cases; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.registered_cases (
    id bigint NOT NULL,
    lowerct_id bigint NOT NULL,
    diary_no bigint,
    fil_no character varying(12),
    entuser bigint NOT NULL,
    entdt timestamp with time zone,
    casetype_id bigint NOT NULL,
    case_no bigint NOT NULL,
    case_year bigint NOT NULL,
    display public.registered_cases_display DEFAULT 'Y'::public.registered_cases_display NOT NULL
);


ALTER TABLE public.registered_cases OWNER TO postgres;

--
-- Name: registered_cases_a; Type: TABLE; Schema: public; Owner: dev
--

CREATE TABLE public.registered_cases_a (
    id bigint,
    lowerct_id bigint,
    diary_no bigint,
    fil_no character varying(12),
    entuser bigint,
    entdt timestamp with time zone,
    casetype_id bigint,
    case_no bigint,
    case_year bigint,
    display public.registered_cases_display
);


ALTER TABLE public.registered_cases_a OWNER TO dev;

--
-- Name: registered_cases_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.registered_cases_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.registered_cases_id_seq OWNER TO postgres;

--
-- Name: registered_cases_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.registered_cases_id_seq OWNED BY public.registered_cases.id;


--
-- Name: registration_track; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.registration_track (
    id bigint NOT NULL,
    diary_no bigint,
    num_to_register bigint,
    registration_number_alloted character varying(200),
    usercode bigint,
    reg_date timestamp with time zone,
    registration_year bigint
);


ALTER TABLE public.registration_track OWNER TO postgres;

--
-- Name: registration_track_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.registration_track_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.registration_track_id_seq OWNER TO postgres;

--
-- Name: registration_track_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.registration_track_id_seq OWNED BY public.registration_track.id;


--
-- Name: relied_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.relied_details (
    relied_id bigint NOT NULL,
    lowerct_id bigint NOT NULL,
    relied_court bigint NOT NULL,
    relied_case_type bigint NOT NULL,
    relied_case_no bigint NOT NULL,
    relied_case_year bigint NOT NULL,
    relied_state bigint NOT NULL,
    relied_district bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL
);


ALTER TABLE public.relied_details OWNER TO postgres;

--
-- Name: relied_details_relied_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.relied_details_relied_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.relied_details_relied_id_seq OWNER TO postgres;

--
-- Name: relied_details_relied_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.relied_details_relied_id_seq OWNED BY public.relied_details.relied_id;


--
-- Name: renewed_caveat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.renewed_caveat (
    id bigint NOT NULL,
    old_caveat_no bigint NOT NULL,
    new_caveat_no bigint NOT NULL,
    renew_date timestamp with time zone,
    renew_user bigint NOT NULL
);


ALTER TABLE public.renewed_caveat OWNER TO postgres;

--
-- Name: renewed_caveat_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.renewed_caveat_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.renewed_caveat_id_seq OWNER TO postgres;

--
-- Name: renewed_caveat_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.renewed_caveat_id_seq OWNED BY public.renewed_caveat.id;


--
-- Name: requistion_upload; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.requistion_upload (
    id bigint NOT NULL,
    req_id bigint,
    file_path character varying(100),
    usercode bigint,
    entry_date timestamp with time zone,
    ip character varying(45),
    remarks character varying(100),
    is_active bigint DEFAULT '1'::bigint NOT NULL
);


ALTER TABLE public.requistion_upload OWNER TO postgres;

--
-- Name: requistion_upload_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.requistion_upload_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.requistion_upload_id_seq OWNER TO postgres;

--
-- Name: requistion_upload_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.requistion_upload_id_seq OWNED BY public.requistion_upload.id;


--
-- Name: restored; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.restored (
    entry_date timestamp with time zone,
    diary_no bigint,
    fil_no character varying(16) NOT NULL,
    res_by_diary_no bigint,
    fil_no_res_by character varying(16) NOT NULL,
    diary_next_dt date,
    conn_next_dt date,
    judges character varying(100) DEFAULT '0'::character varying,
    pet character varying(100) DEFAULT ''::character varying NOT NULL,
    res character varying(100) DEFAULT ''::character varying NOT NULL,
    disp_month bigint DEFAULT 0,
    disp_year bigint DEFAULT 0,
    dispjud bigint DEFAULT 0,
    disp_dt date,
    disp_type bigint DEFAULT 0,
    disp_judges character varying(100) DEFAULT '0'::character varying,
    disp_crtstat character(1),
    disp_camnt bigint NOT NULL,
    disp_ent_dt timestamp with time zone,
    disp_ord_dt date,
    disp_usercode bigint,
    reg_dt timestamp with time zone,
    restore_reason character varying(50),
    disp_rj_dt date,
    diary_rec_dt timestamp with time zone,
    usercode bigint NOT NULL
);


ALTER TABLE public.restored OWNER TO postgres;

--
-- Name: rgo_default; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rgo_default (
    fil_no bigint,
    conn_key bigint,
    reason character varying(100) NOT NULL,
    fil_no2 bigint,
    remove_def character(1) DEFAULT 'N'::bpchar,
    remove_def_dt timestamp with time zone,
    ent_dt timestamp with time zone,
    rgo_updated_by bigint NOT NULL,
    hcourt_no character varying(500),
    court_type character(1)
);


ALTER TABLE public.rgo_default OWNER TO postgres;

--
-- Name: rgo_default_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rgo_default_history (
    fil_no bigint,
    conn_key bigint,
    reason character varying(100) NOT NULL,
    fil_no2 bigint,
    remove_def character(1) DEFAULT 'N'::bpchar,
    remove_def_dt timestamp with time zone,
    ent_dt timestamp with time zone,
    rgo_updated_by bigint NOT NULL,
    removed_by bigint NOT NULL,
    removed_on timestamp with time zone,
    hcourt_no character varying(500),
    court_type character(1)
);


ALTER TABLE public.rgo_default_history OWNER TO postgres;

--
-- Name: sc_working_days_23052019; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sc_working_days_23052019 (
    id bigint NOT NULL,
    working_date date,
    is_nmd smallint DEFAULT '0'::smallint NOT NULL,
    is_holiday smallint DEFAULT '0'::smallint NOT NULL,
    holiday_description character varying(200),
    updated_by bigint DEFAULT '1'::bigint NOT NULL,
    updated_on timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    misc_dt date,
    nmd_dt date,
    sec_list_dt date,
    misc_dt1 date,
    misc_dt2 date,
    holiday_for_registry smallint
);


ALTER TABLE public.sc_working_days_23052019 OWNER TO postgres;

--
-- Name: sc_working_days_23052019_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sc_working_days_23052019_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sc_working_days_23052019_id_seq OWNER TO postgres;

--
-- Name: sc_working_days_23052019_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sc_working_days_23052019_id_seq OWNED BY public.sc_working_days_23052019.id;


--
-- Name: scan_movement; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.scan_movement (
    id bigint NOT NULL,
    dairy_no character varying(55) NOT NULL,
    list_dt date,
    roster_id character varying(45) NOT NULL,
    item_no bigint NOT NULL,
    movement_flag character varying(10) NOT NULL,
    event_type character varying(25),
    user_id bigint NOT NULL,
    ip_address character varying(45) NOT NULL,
    is_active character varying(1) NOT NULL,
    entry_date_time timestamp with time zone
);


ALTER TABLE public.scan_movement OWNER TO postgres;

--
-- Name: scan_movement_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.scan_movement_history (
    id bigint NOT NULL,
    scan_movement_id bigint NOT NULL,
    dairy_no character varying(55) NOT NULL,
    list_dt date,
    roster_id character varying(45) NOT NULL,
    item_no bigint NOT NULL,
    movement_flag character varying(10) NOT NULL,
    event_type character varying(25),
    user_id bigint NOT NULL,
    ip_address character varying(45) NOT NULL,
    is_active character varying(1) NOT NULL,
    entry_date_time timestamp with time zone
);


ALTER TABLE public.scan_movement_history OWNER TO postgres;

--
-- Name: scan_movement_history_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.scan_movement_history_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.scan_movement_history_id_seq OWNER TO postgres;

--
-- Name: scan_movement_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.scan_movement_history_id_seq OWNED BY public.scan_movement_history.id;


--
-- Name: scan_movement_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.scan_movement_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.scan_movement_id_seq OWNER TO postgres;

--
-- Name: scan_movement_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.scan_movement_id_seq OWNED BY public.scan_movement.id;


--
-- Name: sclsc_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sclsc_details (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    sclsc_diary_no bigint NOT NULL,
    sclsc_diary_year bigint NOT NULL,
    sclsc_ent_dt timestamp with time zone,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    create_modify timestamp without time zone,
    updated_by bigint,
    updated_by_ip text
);


ALTER TABLE public.sclsc_details OWNER TO postgres;

--
-- Name: sclsc_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sclsc_details_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sclsc_details_id_seq OWNER TO postgres;

--
-- Name: sclsc_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sclsc_details_id_seq OWNED BY public.sclsc_details.id;


--
-- Name: scordermain; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.scordermain (
    casetype character varying(100),
    caseno character varying(200),
    caseyr bigint,
    petname character varying(80),
    resname character varying(80),
    juddate character varying(10),
    filename numeric(18,0),
    number character varying(20),
    jud1 character varying(60),
    jud2 character varying(60),
    jud3 character varying(60),
    jud4 character varying(60),
    jud5 character varying(60),
    reportable character(1),
    id character varying(25),
    diary_number character varying(10),
    diary_year character varying(5),
    typecode character varying(2),
    cis_typecode character varying(3),
    dn bigint NOT NULL,
    dn_zero bigint NOT NULL,
    id_dn bigint NOT NULL,
    order_type character varying(45) DEFAULT 'judgment'::character varying NOT NULL,
    usercode bigint,
    ent_dt timestamp with time zone
);


ALTER TABLE public.scordermain OWNER TO postgres;

--
-- Name: COLUMN scordermain.dn_zero; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.scordermain.dn_zero IS '1 - new diary number as per njdg requirement, 0 - diary no. already in dn column';


--
-- Name: scordermain_id_dn_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.scordermain_id_dn_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.scordermain_id_dn_seq OWNER TO postgres;

--
-- Name: scordermain_id_dn_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.scordermain_id_dn_seq OWNED BY public.scordermain.id_dn;


--
-- Name: section_id_change; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.section_id_change (
    id bigint NOT NULL,
    diary_no bigint,
    main_tbl_section_id bigint,
    section_code_func bigint,
    entry_time timestamp with time zone
);


ALTER TABLE public.section_id_change OWNER TO postgres;

--
-- Name: section_id_change_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.section_id_change_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.section_id_change_id_seq OWNER TO postgres;

--
-- Name: section_id_change_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.section_id_change_id_seq OWNED BY public.section_id_change.id;


--
-- Name: sensitive_cases; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sensitive_cases (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    reason character varying(500) NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15)
);


ALTER TABLE public.sensitive_cases OWNER TO postgres;

--
-- Name: sensitive_cases_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sensitive_cases_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sensitive_cases_id_seq OWNER TO postgres;

--
-- Name: sensitive_cases_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sensitive_cases_id_seq OWNED BY public.sensitive_cases.id;


--
-- Name: sentence_period; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sentence_period (
    diary_no bigint,
    sentence_yr bigint,
    ucode bigint NOT NULL,
    entdt timestamp with time zone,
    sentence_mth bigint NOT NULL,
    lower_court_id bigint NOT NULL,
    id bigint NOT NULL,
    accused_id bigint NOT NULL,
    display character varying(1) NOT NULL
);


ALTER TABLE public.sentence_period OWNER TO postgres;

--
-- Name: sentence_period_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sentence_period_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sentence_period_id_seq OWNER TO postgres;

--
-- Name: sentence_period_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sentence_period_id_seq OWNED BY public.sentence_period.id;


--
-- Name: sentence_undergone; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sentence_undergone (
    id bigint NOT NULL,
    sentence_period_id bigint NOT NULL,
    status character varying(1),
    usercode bigint,
    ugone_yr bigint,
    entdt timestamp with time zone,
    ugone_mon bigint,
    ugone_day bigint,
    frm_date date,
    to_date date,
    sen_display character varying(1),
    rem character varying(200)
);


ALTER TABLE public.sentence_undergone OWNER TO postgres;

--
-- Name: sentence_undergone_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sentence_undergone_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sentence_undergone_id_seq OWNER TO postgres;

--
-- Name: sentence_undergone_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sentence_undergone_id_seq OWNED BY public.sentence_undergone.id;


--
-- Name: showlcd; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.showlcd (
    court smallint NOT NULL,
    mf character varying(5),
    cl_dt date NOT NULL,
    csno character varying(100) NOT NULL,
    parties text NOT NULL,
    clno character varying(10) NOT NULL,
    msg text NOT NULL,
    ent_dt date NOT NULL,
    ent_dttime timestamp with time zone,
    judges_list character varying(100) DEFAULT '0'::character varying NOT NULL,
    fil_no character varying(15) DEFAULT '0'::character varying NOT NULL,
    jcodes character varying(25) NOT NULL,
    sbdb character varying(3) NOT NULL,
    ent_by bigint NOT NULL,
    is_mentioning character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.showlcd OWNER TO postgres;

--
-- Name: showlcd_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.showlcd_history (
    court smallint NOT NULL,
    mf character varying(5),
    cl_dt date,
    csno character varying(100) NOT NULL,
    parties text NOT NULL,
    clno character varying(10) NOT NULL,
    msg text NOT NULL,
    ent_dt date,
    ent_dttime timestamp with time zone,
    judges_list character varying(100) DEFAULT '0'::character varying NOT NULL,
    fil_no character varying(15) DEFAULT '0'::character varying NOT NULL,
    jcodes character varying(25) NOT NULL,
    sbdb character varying(3) NOT NULL,
    ent_by bigint NOT NULL,
    is_mentioning character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.showlcd_history OWNER TO postgres;

--
-- Name: sign_document; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sign_document (
    id bigint NOT NULL,
    public_key text NOT NULL,
    data text NOT NULL,
    sign_data text NOT NULL,
    dsc_serial_no text NOT NULL,
    dsc_name text NOT NULL
);


ALTER TABLE public.sign_document OWNER TO postgres;

--
-- Name: sign_document_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sign_document_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sign_document_id_seq OWNER TO postgres;

--
-- Name: sign_document_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sign_document_id_seq OWNED BY public.sign_document.id;


--
-- Name: similarity_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.similarity_details (
    id bigint NOT NULL,
    diary_no bigint,
    sim_diary_no bigint,
    status character varying(500),
    remarks character varying(1000),
    propose_for character varying(500),
    ent_by bigint,
    ent_on timestamp with time zone,
    or_remarks character varying(1000)
);


ALTER TABLE public.similarity_details OWNER TO postgres;

--
-- Name: similarity_details_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.similarity_details_history (
    id bigint NOT NULL,
    diary_no bigint,
    sim_diary_no bigint,
    status character varying(500),
    remarks character varying(1000),
    propose_for character varying(500),
    ent_by bigint,
    ent_on timestamp with time zone,
    mod_by bigint,
    mod_on timestamp with time zone,
    or_remarks character varying(1000)
);


ALTER TABLE public.similarity_details_history OWNER TO postgres;

--
-- Name: similarity_details_history_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.similarity_details_history_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.similarity_details_history_id_seq OWNER TO postgres;

--
-- Name: similarity_details_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.similarity_details_history_id_seq OWNED BY public.similarity_details_history.id;


--
-- Name: similarity_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.similarity_details_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.similarity_details_id_seq OWNER TO postgres;

--
-- Name: similarity_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.similarity_details_id_seq OWNED BY public.similarity_details.id;


--
-- Name: single_judge_advance_cl_printed; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.single_judge_advance_cl_printed (
    id bigint NOT NULL,
    from_dt date,
    to_dt date,
    weekly_no bigint NOT NULL,
    weekly_year bigint NOT NULL,
    usercode bigint DEFAULT '0'::bigint NOT NULL,
    ent_time timestamp with time zone,
    is_active smallint DEFAULT '1'::smallint NOT NULL
);


ALTER TABLE public.single_judge_advance_cl_printed OWNER TO postgres;

--
-- Name: single_judge_advance_cl_printed_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.single_judge_advance_cl_printed_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.single_judge_advance_cl_printed_id_seq OWNER TO postgres;

--
-- Name: single_judge_advance_cl_printed_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.single_judge_advance_cl_printed_id_seq OWNED BY public.single_judge_advance_cl_printed.id;


--
-- Name: single_judge_advanced_drop_note; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.single_judge_advanced_drop_note (
    id bigint NOT NULL,
    cl_date date,
    from_dt date,
    to_dt date,
    clno bigint NOT NULL,
    diary_no bigint NOT NULL,
    nrs character varying(75),
    usercode bigint NOT NULL,
    ent_dt timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    display character(1) DEFAULT 'Y'::bpchar NOT NULL,
    mf character(1),
    update_time timestamp with time zone,
    update_user character varying(5) NOT NULL,
    so_user character varying(5) NOT NULL,
    so_time timestamp with time zone,
    part bigint NOT NULL,
    board_type character(1) DEFAULT 'S'::bpchar
);


ALTER TABLE public.single_judge_advanced_drop_note OWNER TO postgres;

--
-- Name: single_judge_advanced_drop_note_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.single_judge_advanced_drop_note_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.single_judge_advanced_drop_note_id_seq OWNER TO postgres;

--
-- Name: single_judge_advanced_drop_note_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.single_judge_advanced_drop_note_id_seq OWNED BY public.single_judge_advanced_drop_note.id;


--
-- Name: sms_drop_cl; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sms_drop_cl (
    mobile bigint NOT NULL,
    diary_no bigint,
    next_dt date,
    court bigint NOT NULL,
    roster_id bigint,
    brd_slno bigint NOT NULL,
    ent_time timestamp with time zone,
    cno character varying(80),
    qry_from character varying(10) NOT NULL,
    sent_to_smspool character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.sms_drop_cl OWNER TO postgres;

--
-- Name: sms_hc_cl; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sms_hc_cl (
    mobile bigint NOT NULL,
    diary_no bigint,
    next_dt date,
    mainhead character(1) NOT NULL,
    court character varying(24),
    roster_id bigint,
    brd_slno bigint NOT NULL,
    ent_time timestamp with time zone,
    cno character varying(80),
    pet_name character varying(100) NOT NULL,
    res_name character varying(100) NOT NULL,
    qry_from character varying(40) NOT NULL,
    sent_to_smspool character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.sms_hc_cl OWNER TO postgres;

--
-- Name: sms_hc_cl_17042023; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sms_hc_cl_17042023 (
    mobile bigint NOT NULL,
    diary_no bigint,
    next_dt date,
    mainhead character(1) NOT NULL,
    court character varying(24),
    roster_id bigint,
    brd_slno bigint NOT NULL,
    ent_time timestamp with time zone,
    cno character varying(80),
    pet_name character varying(100) NOT NULL,
    res_name character varying(100) NOT NULL,
    qry_from character varying(40) NOT NULL,
    sent_to_smspool character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.sms_hc_cl_17042023 OWNER TO postgres;

--
-- Name: sms_pool; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sms_pool (
    id bigint NOT NULL,
    mobile bigint NOT NULL,
    msg text NOT NULL,
    table_name character varying(40) NOT NULL,
    c_status character(1) DEFAULT 'N'::bpchar,
    ent_time timestamp with time zone,
    update_time timestamp with time zone,
    template_id character varying(45)
);


ALTER TABLE public.sms_pool OWNER TO postgres;

--
-- Name: sms_pool_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sms_pool_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sms_pool_id_seq OWNER TO postgres;

--
-- Name: sms_pool_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sms_pool_id_seq OWNED BY public.sms_pool.id;


--
-- Name: sms_weekly; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sms_weekly (
    id bigint NOT NULL,
    mobile bigint NOT NULL,
    aor_code bigint,
    list_number bigint,
    list_year bigint,
    diary_numbers text,
    display character(1) DEFAULT 'Y'::bpchar,
    created_on timestamp with time zone,
    created_by bigint,
    updated_on timestamp with time zone,
    updated_by bigint,
    update_counter bigint,
    sent_to_smspool character(1) DEFAULT 'N'::bpchar,
    email character varying(50),
    email_sent character(1) DEFAULT 'N'::bpchar,
    email_sent_on timestamp with time zone,
    email_error character varying(100)
);


ALTER TABLE public.sms_weekly OWNER TO postgres;

--
-- Name: sms_weekly_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sms_weekly_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sms_weekly_id_seq OWNER TO postgres;

--
-- Name: sms_weekly_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sms_weekly_id_seq OWNED BY public.sms_weekly.id;


--
-- Name: special_category_filing; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.special_category_filing (
    id bigint NOT NULL,
    diary_no bigint,
    ref_special_category_filing_id bigint,
    display character varying(5),
    updated_by bigint,
    updated_on timestamp with time zone,
    create_modify timestamp without time zone,
    updated_by_ip text
);


ALTER TABLE public.special_category_filing OWNER TO postgres;

--
-- Name: special_category_filing_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.special_category_filing_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.special_category_filing_id_seq OWNER TO postgres;

--
-- Name: special_category_filing_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.special_category_filing_id_seq OWNED BY public.special_category_filing.id;


--
-- Name: submaster_old; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.submaster_old (
    id bigint DEFAULT '0'::bigint NOT NULL,
    subcode1 bigint DEFAULT 0,
    subcode2 bigint DEFAULT 0,
    subcode3 bigint DEFAULT '0'::bigint,
    subcode4 bigint DEFAULT '0'::bigint,
    sub_name1 character varying(250) NOT NULL,
    short_description character varying(50),
    sub_name2 character varying(250) NOT NULL,
    sub_name3 character varying(250) NOT NULL,
    sub_name4 character varying(250) NOT NULL,
    subject_description character varying(250),
    category_description character varying(600),
    display character(1),
    flag character(1) NOT NULL,
    list_display character varying(100) NOT NULL,
    updated_on timestamp with time zone,
    id_sc_old bigint NOT NULL,
    subject_sc_old character varying(6) NOT NULL,
    category_sc_old character varying(6) NOT NULL,
    subcode1_hc bigint,
    subcode2_hc bigint,
    subcode3_hc bigint,
    subcode4_hc bigint,
    match_id bigint,
    main_head character(1) NOT NULL,
    flag_use character(1) DEFAULT 'S'::bpchar NOT NULL,
    old_sc_c_kk bigint NOT NULL,
    sub_name1_hindi character varying(250),
    sub_name4_hindi character varying(250)
);


ALTER TABLE public.submaster_old OWNER TO postgres;

--
-- Name: tbl_court_requisition; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tbl_court_requisition (
    id bigint NOT NULL,
    court_number bigint,
    court_username character varying(245),
    remark1 text,
    remark2 text,
    current_status character varying(45) DEFAULT 'pending'::character varying,
    section character varying(45),
    user_type bigint DEFAULT '1'::bigint NOT NULL,
    itemno bigint,
    itemdate date,
    request_file character varying(255),
    request_close_datetime timestamp with time zone,
    user_ip character varying(245),
    urgent character varying(45),
    court_bench character varying(45),
    created_on timestamp with time zone,
    created_by character varying(45),
    updated_on timestamp with time zone,
    updated_by character varying(145),
    status bigint DEFAULT '1'::bigint,
    alternate_number character varying(10),
    diary_no bigint,
    advocate_name character varying(100),
    appearing_for character varying(100),
    party_serial_no character varying(50)
);


ALTER TABLE public.tbl_court_requisition OWNER TO postgres;

--
-- Name: COLUMN tbl_court_requisition.user_type; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.tbl_court_requisition.user_type IS '1 for court assitant and 2 for advocate';


--
-- Name: tbl_court_requisition_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tbl_court_requisition_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tbl_court_requisition_id_seq OWNER TO postgres;

--
-- Name: tbl_court_requisition_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tbl_court_requisition_id_seq OWNED BY public.tbl_court_requisition.id;


--
-- Name: tbl_library_section; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tbl_library_section (
    id bigint NOT NULL,
    library_section_name character varying(145),
    status bigint
);


ALTER TABLE public.tbl_library_section OWNER TO postgres;

--
-- Name: tbl_requisition_department; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tbl_requisition_department (
    id bigint NOT NULL,
    requisition_dep_name character varying(145),
    status bigint DEFAULT '1'::bigint
);


ALTER TABLE public.tbl_requisition_department OWNER TO postgres;

--
-- Name: tbl_requisition_interactions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tbl_requisition_interactions (
    id bigint NOT NULL,
    requisition_id character varying(45),
    interaction_remarks text,
    request_file character varying(255),
    created_on timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    created_by character varying(245),
    interaction_status character varying(25) DEFAULT 'pending'::character varying,
    read_status bigint DEFAULT '0'::bigint,
    read_staus_time time without time zone,
    read_status_librarian boolean DEFAULT false,
    read_status_librarian_time time without time zone,
    itemno character varying(100),
    interaction_ip character varying(45)
);


ALTER TABLE public.tbl_requisition_interactions OWNER TO postgres;

--
-- Name: tbl_requisition_request; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tbl_requisition_request (
    id bigint NOT NULL,
    request_id bigint,
    request_user_id bigint,
    req_user_type character varying(45) DEFAULT 'ADVOCATE'::character varying,
    request_data character varying(45),
    request_file character varying(45),
    created_on timestamp with time zone,
    issue_type character varying(45),
    issue_date timestamp with time zone,
    issued_by character varying(45),
    issued_remark character varying(45),
    status bigint DEFAULT '1'::bigint
);


ALTER TABLE public.tbl_requisition_request OWNER TO postgres;

--
-- Name: temp; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.temp (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    dacode bigint NOT NULL
);


ALTER TABLE public.temp OWNER TO postgres;

--
-- Name: temp_sclsc_cvs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.temp_sclsc_cvs (
    sno bigint NOT NULL,
    sclsc_dno character varying(100),
    sc_dno bigint,
    sc_dyr bigint,
    pet character varying(100),
    res character varying(100)
);


ALTER TABLE public.temp_sclsc_cvs OWNER TO postgres;

--
-- Name: temp_table; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.temp_table (
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    dacode bigint NOT NULL,
    section_id bigint
);


ALTER TABLE public.temp_table OWNER TO postgres;

--
-- Name: tempo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tempo (
    id bigint NOT NULL,
    dn character varying(50) NOT NULL,
    dy character varying(50) NOT NULL,
    ct character varying(50) NOT NULL,
    cn character varying(50) NOT NULL,
    cy character varying(50) NOT NULL,
    dated character varying(50) NOT NULL,
    jm character varying(50) NOT NULL,
    jt character varying(50) NOT NULL,
    diary_no bigint NOT NULL,
    usercode bigint,
    ent_dt timestamp with time zone
);


ALTER TABLE public.tempo OWNER TO postgres;

--
-- Name: tempo_deleted; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tempo_deleted (
    id bigint NOT NULL,
    dn character varying(50) NOT NULL,
    dy character varying(50) NOT NULL,
    ct character varying(50) NOT NULL,
    cn character varying(50) NOT NULL,
    cy character varying(50) NOT NULL,
    dated character varying(50) NOT NULL,
    jm character varying(50) NOT NULL,
    jt character varying(50) NOT NULL,
    diary_no bigint NOT NULL,
    usercode bigint,
    ent_dt timestamp with time zone,
    deleted_on timestamp with time zone
);


ALTER TABLE public.tempo_deleted OWNER TO postgres;

--
-- Name: tempo_deleted_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tempo_deleted_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tempo_deleted_id_seq OWNER TO postgres;

--
-- Name: tempo_deleted_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tempo_deleted_id_seq OWNED BY public.tempo_deleted.id;


--
-- Name: tempo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tempo_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tempo_id_seq OWNER TO postgres;

--
-- Name: tempo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tempo_id_seq OWNED BY public.tempo.id;


--
-- Name: transactions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transactions (
    id bigint NOT NULL,
    acid bigint NOT NULL,
    event_code bigint NOT NULL,
    event_date date,
    updated_by bigint NOT NULL,
    updated_on timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    remarks character varying(500) NOT NULL,
    updatedip character varying(45)
);


ALTER TABLE public.transactions OWNER TO postgres;

--
-- Name: transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transactions_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transactions_id_seq OWNER TO postgres;

--
-- Name: transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.transactions_id_seq OWNED BY public.transactions.id;


--
-- Name: transcribed_arguments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transcribed_arguments (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    order_dt date,
    file_name character varying(200) NOT NULL,
    create_dt timestamp with time zone,
    create_user bigint NOT NULL,
    display character(1) NOT NULL,
    update_date timestamp with time zone,
    update_by bigint
);


ALTER TABLE public.transcribed_arguments OWNER TO postgres;

--
-- Name: transcribed_arguments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transcribed_arguments_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transcribed_arguments_id_seq OWNER TO postgres;

--
-- Name: transcribed_arguments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.transcribed_arguments_id_seq OWNED BY public.transcribed_arguments.id;


--
-- Name: transfer_old_com_gen_cases; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transfer_old_com_gen_cases (
    diary_no bigint,
    next_dt_old date,
    next_dt_new date,
    tentative_cl_dt_old date,
    tentative_cl_dt_new date,
    listorder bigint NOT NULL,
    conn_key bigint,
    ent_dt timestamp with time zone,
    test2 character varying(10) NOT NULL,
    listorder_new bigint NOT NULL,
    board_type character(1) DEFAULT 'J'::bpchar,
    listtype character(1),
    reason character varying(100)
);


ALTER TABLE public.transfer_old_com_gen_cases OWNER TO postgres;

--
-- Name: transfer_to_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transfer_to_details (
    transfer_to_id bigint NOT NULL,
    lowerct_id bigint NOT NULL,
    transfer_court bigint NOT NULL,
    transfer_case_type bigint NOT NULL,
    transfer_case_no character varying(50) NOT NULL,
    transfer_case_year bigint NOT NULL,
    transfer_state bigint NOT NULL,
    transfer_district bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL
);


ALTER TABLE public.transfer_to_details OWNER TO postgres;

--
-- Name: transfer_to_details_transfer_to_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transfer_to_details_transfer_to_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transfer_to_details_transfer_to_id_seq OWNER TO postgres;

--
-- Name: transfer_to_details_transfer_to_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.transfer_to_details_transfer_to_id_seq OWNED BY public.transfer_to_details.transfer_to_id;


--
-- Name: tw_comp_not; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tw_comp_not (
    id bigint NOT NULL,
    tw_o_r_id bigint NOT NULL,
    tw_sn_to bigint,
    sendto_state bigint NOT NULL,
    sendto_district bigint NOT NULL,
    copy_type bigint,
    send_to_type character varying(1) NOT NULL,
    serve bigint NOT NULL,
    ser_type bigint NOT NULL,
    ser_date date,
    ser_dt_ent_dt timestamp with time zone,
    ack_user_id bigint NOT NULL,
    dis_da_dt timestamp with time zone,
    da_rec_dt timestamp with time zone,
    ack_id bigint NOT NULL,
    remark character varying(100),
    l_ljs_rem character varying(300),
    l_hjs_rem character varying(300),
    l_ljs_p_d character varying(1) NOT NULL,
    l_hjs_p_d character varying(1) NOT NULL,
    l_ljs_pt character varying(100) NOT NULL,
    l_hjs_pt character varying(100) NOT NULL,
    t_ljs_p_d character varying(1) NOT NULL,
    t_hjs_p_d character varying(1) NOT NULL,
    t_ljs_rem character varying(100) NOT NULL,
    t_hjs_rem character varying(100) NOT NULL,
    station bigint NOT NULL,
    weight bigint NOT NULL,
    stamp bigint NOT NULL,
    dis_remark character varying(200) NOT NULL,
    dispatch_user_id bigint NOT NULL,
    dispatch_dt timestamp with time zone,
    dispatch_id bigint NOT NULL,
    barcode character varying(13),
    m_d bigint NOT NULL,
    send_mail_dt timestamp with time zone,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    bc_update_by character varying(45),
    bc_update_on timestamp with time zone
);


ALTER TABLE public.tw_comp_not OWNER TO postgres;

--
-- Name: tw_comp_not_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tw_comp_not_history (
    id bigint NOT NULL,
    tw_comp_not_id bigint NOT NULL,
    tw_o_r_id bigint NOT NULL,
    tw_sn_to bigint,
    sendto_state bigint NOT NULL,
    sendto_district bigint NOT NULL,
    copy_type bigint,
    send_to_type character varying(1) NOT NULL,
    serve bigint NOT NULL,
    ser_type bigint NOT NULL,
    ser_date date,
    ser_dt_ent_dt timestamp with time zone,
    ack_user_id bigint NOT NULL,
    dis_da_dt timestamp with time zone,
    da_rec_dt timestamp with time zone,
    ack_id bigint NOT NULL,
    remark character varying(100),
    l_ljs_rem character varying(300),
    l_hjs_rem character varying(300),
    l_ljs_p_d character varying(1) NOT NULL,
    l_hjs_p_d character varying(1) NOT NULL,
    l_ljs_pt character varying(100) NOT NULL,
    l_hjs_pt character varying(100) NOT NULL,
    t_ljs_p_d character varying(1) NOT NULL,
    t_hjs_p_d character varying(1) NOT NULL,
    t_ljs_rem character varying(100) NOT NULL,
    t_hjs_rem character varying(100) NOT NULL,
    station bigint NOT NULL,
    weight bigint NOT NULL,
    stamp bigint NOT NULL,
    dis_remark character varying(200) NOT NULL,
    dispatch_user_id bigint NOT NULL,
    dispatch_dt timestamp with time zone,
    dispatch_id bigint NOT NULL,
    barcode character varying(13),
    m_d bigint NOT NULL,
    send_mail_dt timestamp with time zone,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    update_by bigint NOT NULL,
    update_on timestamp with time zone,
    p_id bigint,
    pid_year bigint
);


ALTER TABLE public.tw_comp_not_history OWNER TO postgres;

--
-- Name: tw_comp_not_history_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tw_comp_not_history_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tw_comp_not_history_id_seq OWNER TO postgres;

--
-- Name: tw_comp_not_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tw_comp_not_history_id_seq OWNED BY public.tw_comp_not_history.id;


--
-- Name: tw_comp_not_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tw_comp_not_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tw_comp_not_id_seq OWNER TO postgres;

--
-- Name: tw_comp_not_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tw_comp_not_id_seq OWNED BY public.tw_comp_not.id;


--
-- Name: tw_not_pen_sta; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tw_not_pen_sta (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    ck_rec_dt date,
    ck_cl_dt date,
    ck_hd bigint NOT NULL
);


ALTER TABLE public.tw_not_pen_sta OWNER TO postgres;

--
-- Name: tw_not_pen_sta_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tw_not_pen_sta_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tw_not_pen_sta_id_seq OWNER TO postgres;

--
-- Name: tw_not_pen_sta_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tw_not_pen_sta_id_seq OWNED BY public.tw_not_pen_sta.id;


--
-- Name: tw_o_r; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tw_o_r (
    id bigint NOT NULL,
    tw_org_id bigint,
    del_type character varying(1),
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    sign_date timestamp with time zone,
    public_key character varying(11),
    data character varying(11),
    sign_data character varying(11),
    dsc_serial_no character varying(11),
    dsc_name character varying(11),
    mode_path character varying(100) NOT NULL
);


ALTER TABLE public.tw_o_r OWNER TO postgres;

--
-- Name: tw_o_r_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tw_o_r_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tw_o_r_id_seq OWNER TO postgres;

--
-- Name: tw_o_r_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tw_o_r_id_seq OWNED BY public.tw_o_r.id;


--
-- Name: tw_pro_desc; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tw_pro_desc (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    docnum bigint NOT NULL,
    docyear bigint NOT NULL,
    pet_res character varying(1) NOT NULL,
    sr_no bigint NOT NULL,
    type character varying(2) NOT NULL,
    usercode bigint NOT NULL,
    ent_dt timestamp with time zone
);


ALTER TABLE public.tw_pro_desc OWNER TO postgres;

--
-- Name: tw_pro_desc_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tw_pro_desc_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tw_pro_desc_id_seq OWNER TO postgres;

--
-- Name: tw_pro_desc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tw_pro_desc_id_seq OWNED BY public.tw_pro_desc.id;


--
-- Name: tw_tal_del; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tw_tal_del (
    id bigint NOT NULL,
    process_id bigint NOT NULL,
    diary_no bigint,
    sr_no character varying(10) NOT NULL,
    pet_res character varying(1) NOT NULL,
    rec_dt date,
    name character varying(400) NOT NULL,
    address character varying(700) NOT NULL,
    nt_type character varying(200) NOT NULL,
    print bigint NOT NULL,
    amount bigint NOT NULL,
    user_id bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    amt_wor character varying(5000) NOT NULL,
    tal_state bigint NOT NULL,
    tal_district bigint NOT NULL,
    fixed_for date,
    sub_tal character varying(300),
    lok_reg bigint DEFAULT 0 NOT NULL,
    enrol_no character varying(11) NOT NULL,
    enrol_yr character varying(4) NOT NULL,
    order_dt date,
    office_notice_rpt character varying(1) NOT NULL,
    notice_path character varying(100) NOT NULL,
    web_status bigint,
    individual_multiple bigint NOT NULL,
    published_by bigint,
    userip character varying(45),
    published_on timestamp with time zone
);


ALTER TABLE public.tw_tal_del OWNER TO postgres;

--
-- Name: tw_tal_del_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tw_tal_del_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tw_tal_del_id_seq OWNER TO postgres;

--
-- Name: tw_tal_del_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tw_tal_del_id_seq OWNED BY public.tw_tal_del.id;


--
-- Name: update_heardt_reason; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.update_heardt_reason (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    reason character varying(100) NOT NULL,
    usercode bigint NOT NULL,
    ent_dt timestamp with time zone
);


ALTER TABLE public.update_heardt_reason OWNER TO postgres;

--
-- Name: update_heardt_reason_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.update_heardt_reason_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.update_heardt_reason_id_seq OWNER TO postgres;

--
-- Name: update_heardt_reason_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.update_heardt_reason_id_seq OWNED BY public.update_heardt_reason.id;


--
-- Name: users_22092000; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users_22092000 (
    usercode bigint NOT NULL,
    userpass character varying(100),
    name character varying(30) NOT NULL,
    empid bigint,
    service character varying(1) NOT NULL,
    usertype bigint DEFAULT '2'::bigint,
    section bigint NOT NULL,
    udept bigint,
    log_in timestamp with time zone,
    logout timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar,
    jcode bigint DEFAULT 0 NOT NULL,
    nm_alias character varying(500) NOT NULL,
    entdt timestamp with time zone,
    entuser bigint NOT NULL,
    attend public.users_22092000_attend DEFAULT 'P'::public.users_22092000_attend,
    upuser bigint NOT NULL,
    updt timestamp with time zone,
    mobile_no character varying(45),
    email_id character varying(100),
    ip_address character varying(200),
    is_courtmaster character varying(2) DEFAULT 'N'::character varying NOT NULL,
    dob date,
    mobile character varying(45),
    uphoto character varying(200)
);


ALTER TABLE public.users_22092000 OWNER TO postgres;

--
-- Name: users_22092000_usercode_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_22092000_usercode_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_22092000_usercode_seq OWNER TO postgres;

--
-- Name: users_22092000_usercode_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_22092000_usercode_seq OWNED BY public.users_22092000.usercode;


--
-- Name: users_dump; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users_dump (
    usercode bigint NOT NULL,
    userpass character varying(100),
    name character varying(30) NOT NULL,
    empid bigint,
    service character varying(1) NOT NULL,
    usertype bigint DEFAULT '2'::bigint,
    section bigint NOT NULL,
    udept bigint,
    log_in timestamp with time zone,
    logout timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar,
    jcode bigint DEFAULT 0 NOT NULL,
    nm_alias character varying(500) NOT NULL,
    entdt timestamp with time zone,
    entuser bigint NOT NULL,
    attend public.users_dump_attend DEFAULT 'P'::public.users_dump_attend,
    upuser bigint NOT NULL,
    updt timestamp with time zone,
    mobile_no character varying(45),
    email_id character varying(100),
    ip_address character varying(200),
    is_courtmaster character varying(2) DEFAULT 'N'::character varying NOT NULL,
    dob date,
    mobile character varying(45),
    uphoto character varying(200)
);


ALTER TABLE public.users_dump OWNER TO postgres;

--
-- Name: users_dump_usercode_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_dump_usercode_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_dump_usercode_seq OWNER TO postgres;

--
-- Name: users_dump_usercode_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_dump_usercode_seq OWNED BY public.users_dump.usercode;


--
-- Name: vacation_advance_list; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacation_advance_list (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character varying(1),
    next_dt date,
    is_deleted character varying(1),
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15),
    vacation_list_year bigint
);


ALTER TABLE public.vacation_advance_list OWNER TO postgres;

--
-- Name: vacation_advance_list_advocate; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacation_advance_list_advocate (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character varying(1),
    aor_code bigint,
    is_deleted character varying(1),
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15),
    vacation_list_year bigint
);


ALTER TABLE public.vacation_advance_list_advocate OWNER TO postgres;

--
-- Name: vacation_advance_list_advocate_2018; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacation_advance_list_advocate_2018 (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character(1) DEFAULT 'N'::bpchar,
    aor_code bigint,
    is_deleted character(1) DEFAULT 'f'::bpchar NOT NULL,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15)
);


ALTER TABLE public.vacation_advance_list_advocate_2018 OWNER TO postgres;

--
-- Name: vacation_advance_list_advocate_2018_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vacation_advance_list_advocate_2018_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vacation_advance_list_advocate_2018_id_seq OWNER TO postgres;

--
-- Name: vacation_advance_list_advocate_2018_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vacation_advance_list_advocate_2018_id_seq OWNED BY public.vacation_advance_list_advocate_2018.id;


--
-- Name: vacation_advance_list_advocate_2023_backup; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacation_advance_list_advocate_2023_backup (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character(1) DEFAULT 'N'::bpchar,
    aor_code bigint,
    is_deleted character(1) DEFAULT 'f'::bpchar NOT NULL,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15)
);


ALTER TABLE public.vacation_advance_list_advocate_2023_backup OWNER TO postgres;

--
-- Name: vacation_advance_list_advocate_2023_backup_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vacation_advance_list_advocate_2023_backup_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vacation_advance_list_advocate_2023_backup_id_seq OWNER TO postgres;

--
-- Name: vacation_advance_list_advocate_2023_backup_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vacation_advance_list_advocate_2023_backup_id_seq OWNED BY public.vacation_advance_list_advocate_2023_backup.id;


--
-- Name: vacation_advance_list_advocate_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacation_advance_list_advocate_log (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character varying(1),
    aor_code bigint,
    is_deleted character varying(1),
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15),
    vacation_list_year bigint
);


ALTER TABLE public.vacation_advance_list_advocate_log OWNER TO postgres;

--
-- Name: vacation_advance_list_advocate_log_old; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacation_advance_list_advocate_log_old (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character(1) DEFAULT 'N'::bpchar,
    aor_code bigint,
    is_deleted character(1) DEFAULT 'f'::bpchar NOT NULL,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15),
    vacation_list_year bigint
);


ALTER TABLE public.vacation_advance_list_advocate_log_old OWNER TO postgres;

--
-- Name: vacation_advance_list_advocate_old; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacation_advance_list_advocate_old (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character(1) DEFAULT 'N'::bpchar,
    aor_code bigint,
    is_deleted character(1) DEFAULT 'f'::bpchar NOT NULL,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15),
    vacation_list_year bigint
);


ALTER TABLE public.vacation_advance_list_advocate_old OWNER TO postgres;

--
-- Name: vacation_advance_list_advocate_old_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vacation_advance_list_advocate_old_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vacation_advance_list_advocate_old_id_seq OWNER TO postgres;

--
-- Name: vacation_advance_list_advocate_old_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vacation_advance_list_advocate_old_id_seq OWNED BY public.vacation_advance_list_advocate_old.id;


--
-- Name: vacation_advance_list_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacation_advance_list_log (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character varying(1),
    next_dt date,
    is_deleted character varying(1),
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15),
    vacation_list_year bigint
);


ALTER TABLE public.vacation_advance_list_log OWNER TO postgres;

--
-- Name: vacation_advance_list_log_old; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacation_advance_list_log_old (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character(1) DEFAULT 'N'::bpchar,
    next_dt date,
    is_deleted character(1) DEFAULT 'f'::bpchar NOT NULL,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15),
    vacation_list_year bigint
);


ALTER TABLE public.vacation_advance_list_log_old OWNER TO postgres;

--
-- Name: vacation_advance_list_old; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacation_advance_list_old (
    id bigint NOT NULL,
    diary_no bigint NOT NULL,
    conn_key bigint,
    is_fixed character(1) DEFAULT 'N'::bpchar,
    next_dt date,
    is_deleted character(1) DEFAULT 'f'::bpchar NOT NULL,
    updated_by bigint,
    updated_on timestamp with time zone,
    updated_from_ip character varying(15),
    vacation_list_year bigint
);


ALTER TABLE public.vacation_advance_list_old OWNER TO postgres;

--
-- Name: vacation_advance_list_old_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vacation_advance_list_old_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vacation_advance_list_old_id_seq OWNER TO postgres;

--
-- Name: vacation_advance_list_old_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vacation_advance_list_old_id_seq OWNED BY public.vacation_advance_list_old.id;


--
-- Name: vacation_list_data; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacation_list_data (
    sl_no bigint NOT NULL,
    diary_no bigint,
    list_year bigint NOT NULL
);


ALTER TABLE public.vacation_list_data OWNER TO postgres;

--
-- Name: vacation_registrar_not_ready_cl; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacation_registrar_not_ready_cl (
    diary_no bigint,
    list_dt date,
    user_code bigint,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar,
    reg_jcode bigint
);


ALTER TABLE public.vacation_registrar_not_ready_cl OWNER TO postgres;

--
-- Name: vacation_registrar_pool; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vacation_registrar_pool (
    diary_no bigint NOT NULL,
    user_code bigint,
    ent_dt timestamp with time zone,
    display character(1) DEFAULT 'Y'::bpchar
);


ALTER TABLE public.vacation_registrar_pool OWNER TO postgres;

--
-- Name: vc_room_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vc_room_details (
    id bigint NOT NULL,
    next_dt date,
    roster_id bigint,
    vc_url character varying(200),
    display character(1) DEFAULT 'Y'::bpchar,
    created_by bigint,
    created_on timestamp with time zone,
    updated_by bigint,
    updated_on timestamp with time zone,
    item_numbers_csv text,
    item_numbers text
);


ALTER TABLE public.vc_room_details OWNER TO postgres;

--
-- Name: vc_room_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vc_room_details_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vc_room_details_id_seq OWNER TO postgres;

--
-- Name: vc_room_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vc_room_details_id_seq OWNED BY public.vc_room_details.id;


--
-- Name: vc_stats; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vc_stats (
    id bigint NOT NULL,
    date date,
    filed bigint,
    bench bigint,
    listed_misc bigint,
    listed_regular bigint,
    disposed_misc bigint,
    disposed_regular bigint,
    updated_on timestamp with time zone,
    display character varying(5) DEFAULT 'Y'::character varying
);


ALTER TABLE public.vc_stats OWNER TO postgres;

--
-- Name: vc_stats_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vc_stats_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vc_stats_id_seq OWNER TO postgres;

--
-- Name: vc_stats_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vc_stats_id_seq OWNED BY public.vc_stats.id;


--
-- Name: vc_webcast_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vc_webcast_details (
    id bigint NOT NULL,
    nofn_link character varying(200),
    vcmeet_link character varying(200),
    display character(1),
    is_nofn character(1),
    is_vcmeet character(1),
    updated_on timestamp with time zone,
    courtno bigint,
    sbanch_link character varying(200) DEFAULT '-'::character varying NOT NULL,
    is_sb character(1) DEFAULT 'N'::bpchar NOT NULL,
    is_webex character(1) DEFAULT 'N'::bpchar NOT NULL,
    webex_link character varying(200) DEFAULT '-'::character varying NOT NULL,
    bench_time character varying(10) DEFAULT '10:30 AM'::character varying NOT NULL,
    remark character varying(250) DEFAULT ''::character varying,
    bench_date date
);


ALTER TABLE public.vc_webcast_details OWNER TO postgres;

--
-- Name: vc_webcast_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vc_webcast_details_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vc_webcast_details_id_seq OWNER TO postgres;

--
-- Name: vc_webcast_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vc_webcast_details_id_seq OWNED BY public.vc_webcast_details.id;


--
-- Name: vc_webcast_details_temp; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vc_webcast_details_temp (
    id bigint NOT NULL,
    nofn_link character varying(200),
    vcmeet_link character varying(200),
    display character(1),
    is_nofn character(1),
    is_vcmeet character(1),
    updated_on timestamp with time zone,
    courtno bigint
);


ALTER TABLE public.vc_webcast_details_temp OWNER TO postgres;

--
-- Name: vc_webcast_details_temp_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vc_webcast_details_temp_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vc_webcast_details_temp_id_seq OWNER TO postgres;

--
-- Name: vc_webcast_details_temp_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vc_webcast_details_temp_id_seq OWNED BY public.vc_webcast_details_temp.id;


--
-- Name: vc_webcast_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vc_webcast_history (
    id bigint NOT NULL,
    nofn_link character varying(200),
    vcmeet_link character varying(200),
    display character(1),
    is_nofn character(1),
    is_vcmeet character(1),
    updated_on timestamp with time zone,
    courtno bigint,
    sbanch_link character varying(200) DEFAULT '-'::character varying NOT NULL,
    is_sb character(1) DEFAULT 'N'::bpchar NOT NULL,
    is_webex character(1) DEFAULT 'N'::bpchar NOT NULL,
    webex_link character varying(200) DEFAULT '-'::character varying NOT NULL,
    bench_time character varying(10) DEFAULT '10:30 AM'::character varying NOT NULL,
    remark character varying(250) DEFAULT ''::character varying NOT NULL,
    vc_id character varying(45),
    bench_date date
);


ALTER TABLE public.vc_webcast_history OWNER TO postgres;

--
-- Name: vc_webcast_history_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vc_webcast_history_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vc_webcast_history_id_seq OWNER TO postgres;

--
-- Name: vc_webcast_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vc_webcast_history_id_seq OWNED BY public.vc_webcast_history.id;


--
-- Name: verify_digital_signature; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.verify_digital_signature (
    dsc_name text NOT NULL,
    dsc_serial_no character varying(100) DEFAULT ''::character varying NOT NULL,
    dsc_public_key text NOT NULL,
    dsc_expirey_date character varying(20),
    cmis_user bigint NOT NULL
);


ALTER TABLE public.verify_digital_signature OWNER TO postgres;

--
-- Name: verify_hcor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.verify_hcor (
    diary_no character varying(45) NOT NULL,
    is_verify character(1) DEFAULT 'N'::bpchar,
    remarks character varying(45000),
    verify_by bigint NOT NULL,
    verify_on timestamp with time zone
);


ALTER TABLE public.verify_hcor OWNER TO postgres;

--
-- Name: vernacular_orders_judgments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vernacular_orders_judgments (
    id bigint NOT NULL,
    diary_no bigint DEFAULT '0'::bigint NOT NULL,
    order_date date,
    ref_vernacular_languages_id bigint NOT NULL,
    pdf_name character varying(200),
    user_code bigint DEFAULT '0'::bigint NOT NULL,
    entry_date timestamp with time zone,
    order_type character(1) NOT NULL,
    web_status bigint NOT NULL,
    display character varying(1) DEFAULT 'Y'::character varying NOT NULL,
    ordertextdata text
);


ALTER TABLE public.vernacular_orders_judgments OWNER TO postgres;

--
-- Name: vernacular_orders_judgments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vernacular_orders_judgments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vernacular_orders_judgments_id_seq OWNER TO postgres;

--
-- Name: vernacular_orders_judgments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vernacular_orders_judgments_id_seq OWNED BY public.vernacular_orders_judgments.id;


--
-- Name: virtual_justice_clock; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.virtual_justice_clock (
    id bigint NOT NULL,
    flag character varying(72) NOT NULL,
    counted_data bigint DEFAULT '0'::bigint NOT NULL,
    list_date date,
    is_active character(1) DEFAULT 'Y'::bpchar NOT NULL,
    ason timestamp with time zone
);


ALTER TABLE public.virtual_justice_clock OWNER TO postgres;

--
-- Name: virtual_justice_clock_casetype; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.virtual_justice_clock_casetype (
    id bigint NOT NULL,
    casecode bigint NOT NULL,
    casename character varying(72) NOT NULL,
    flag character varying(72) NOT NULL,
    counted_data bigint DEFAULT '0'::bigint NOT NULL,
    main_counted_data bigint,
    "0_1_year_old" bigint DEFAULT '0'::bigint NOT NULL,
    main_0_1_year_old bigint DEFAULT '0'::bigint NOT NULL,
    "2_3_year_old" bigint DEFAULT '0'::bigint NOT NULL,
    main_2_3_year_old bigint DEFAULT '0'::bigint NOT NULL,
    "4_5_year_old" bigint DEFAULT '0'::bigint NOT NULL,
    main_4_5_year_old bigint DEFAULT '0'::bigint NOT NULL,
    "6_10_year_old" bigint DEFAULT '0'::bigint NOT NULL,
    main_6_10_year_old bigint DEFAULT '0'::bigint NOT NULL,
    "11_20_year_old" bigint DEFAULT '0'::bigint NOT NULL,
    main_11_20_year_old bigint DEFAULT '0'::bigint NOT NULL,
    "21_30_year_old" bigint DEFAULT '0'::bigint NOT NULL,
    main_21_30_year_old bigint DEFAULT '0'::bigint NOT NULL,
    above_30_year_old bigint DEFAULT '0'::bigint NOT NULL,
    main_above_30_year_old bigint DEFAULT '0'::bigint NOT NULL,
    is_active character(1) DEFAULT 'Y'::bpchar NOT NULL,
    ason timestamp with time zone
);


ALTER TABLE public.virtual_justice_clock_casetype OWNER TO postgres;

--
-- Name: virtual_justice_clock_casetype_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.virtual_justice_clock_casetype_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.virtual_justice_clock_casetype_id_seq OWNER TO postgres;

--
-- Name: virtual_justice_clock_casetype_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.virtual_justice_clock_casetype_id_seq OWNED BY public.virtual_justice_clock_casetype.id;


--
-- Name: virtual_justice_clock_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.virtual_justice_clock_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.virtual_justice_clock_id_seq OWNER TO postgres;

--
-- Name: virtual_justice_clock_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.virtual_justice_clock_id_seq OWNED BY public.virtual_justice_clock.id;


--
-- Name: virtual_justice_clock_main_subject_category; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.virtual_justice_clock_main_subject_category (
    id bigint NOT NULL,
    subcode1 bigint NOT NULL,
    sub_name1 character varying(72) NOT NULL,
    flag character varying(72) NOT NULL,
    counted_data bigint DEFAULT '0'::bigint NOT NULL,
    main_counted_data bigint,
    "0_1_year_old" bigint DEFAULT '0'::bigint NOT NULL,
    main_0_1_year_old bigint DEFAULT '0'::bigint NOT NULL,
    "2_3_year_old" bigint DEFAULT '0'::bigint NOT NULL,
    main_2_3_year_old bigint DEFAULT '0'::bigint NOT NULL,
    "4_5_year_old" bigint DEFAULT '0'::bigint NOT NULL,
    main_4_5_year_old bigint DEFAULT '0'::bigint NOT NULL,
    "6_10_year_old" bigint DEFAULT '0'::bigint NOT NULL,
    main_6_10_year_old bigint DEFAULT '0'::bigint NOT NULL,
    "11_20_year_old" bigint DEFAULT '0'::bigint NOT NULL,
    main_11_20_year_old bigint DEFAULT '0'::bigint NOT NULL,
    "21_30_year_old" bigint DEFAULT '0'::bigint NOT NULL,
    main_21_30_year_old bigint DEFAULT '0'::bigint NOT NULL,
    above_30_year_old bigint DEFAULT '0'::bigint NOT NULL,
    main_above_30_year_old bigint DEFAULT '0'::bigint NOT NULL,
    is_active character(1) DEFAULT 'Y'::bpchar NOT NULL,
    ason timestamp with time zone
);


ALTER TABLE public.virtual_justice_clock_main_subject_category OWNER TO postgres;

--
-- Name: virtual_justice_clock_main_subject_category_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.virtual_justice_clock_main_subject_category_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.virtual_justice_clock_main_subject_category_id_seq OWNER TO postgres;

--
-- Name: virtual_justice_clock_main_subject_category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.virtual_justice_clock_main_subject_category_id_seq OWNED BY public.virtual_justice_clock_main_subject_category.id;


--
-- Name: virtual_justice_clock_scrutiny; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.virtual_justice_clock_scrutiny (
    id bigint NOT NULL,
    flag character varying(72) NOT NULL,
    empid bigint DEFAULT '0'::bigint,
    employee_name character varying(100) DEFAULT '0'::character varying,
    total bigint DEFAULT '0'::bigint,
    efiled_total bigint DEFAULT '0'::bigint,
    pfiling_total bigint DEFAULT '0'::bigint,
    "0_3_days_efile" bigint DEFAULT '0'::bigint,
    "0_3_days_pfile" bigint DEFAULT '0'::bigint,
    "4_7_days_efile" bigint DEFAULT '0'::bigint,
    "4_7_days_pfile" bigint DEFAULT '0'::bigint,
    "7_1_month_efile" bigint DEFAULT '0'::bigint,
    "7_1_month_pfile" bigint DEFAULT '0'::bigint,
    above_month_efile bigint DEFAULT '0'::bigint,
    above_month_pfile bigint DEFAULT '0'::bigint,
    last_week_comp_pfile bigint DEFAULT '0'::bigint,
    last_week_comp_efile bigint DEFAULT '0'::bigint,
    is_active character(1) DEFAULT 'Y'::bpchar NOT NULL,
    ason timestamp with time zone
);


ALTER TABLE public.virtual_justice_clock_scrutiny OWNER TO postgres;

--
-- Name: virtual_justice_clock_scrutiny_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.virtual_justice_clock_scrutiny_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.virtual_justice_clock_scrutiny_id_seq OWNER TO postgres;

--
-- Name: virtual_justice_clock_scrutiny_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.virtual_justice_clock_scrutiny_id_seq OWNED BY public.virtual_justice_clock_scrutiny.id;


--
-- Name: weekly_list; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.weekly_list (
    id bigint NOT NULL,
    item_no bigint,
    diary_no bigint,
    conn_key bigint,
    next_dt date,
    from_dt date,
    to_dt date,
    courtno bigint,
    judges_code character varying(50),
    listorder bigint,
    usercode bigint,
    ent_dt timestamp with time zone,
    weekly_no bigint,
    weekly_year bigint
);


ALTER TABLE public.weekly_list OWNER TO postgres;

--
-- Name: weekly_list_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.weekly_list_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.weekly_list_id_seq OWNER TO postgres;

--
-- Name: weekly_list_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.weekly_list_id_seq OWNED BY public.weekly_list.id;


--
-- Name: whatsapp_pool; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.whatsapp_pool (
    id bigint NOT NULL,
    module character varying(45),
    purpose character varying(45),
    mobile character varying(3000),
    diary_no bigint,
    msg_status character varying(45),
    display character varying(45),
    entry_time character varying(45),
    is_revised character varying(45)
);


ALTER TABLE public.whatsapp_pool OWNER TO postgres;

--
-- Name: whatsapp_pool_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.whatsapp_pool_id_seq
    AS bigint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.whatsapp_pool_id_seq OWNER TO postgres;

--
-- Name: whatsapp_pool_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.whatsapp_pool_id_seq OWNED BY public.whatsapp_pool.id;


--
-- Name: act_master id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.act_master ALTER COLUMN id SET DEFAULT nextval('master.act_master_id_seq'::regclass);


--
-- Name: admin_icmis_usertype_map id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.admin_icmis_usertype_map ALTER COLUMN id SET DEFAULT nextval('master.admin_icmis_usertype_map_id_seq'::regclass);


--
-- Name: amicus_curiae_allotment_direction id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.amicus_curiae_allotment_direction ALTER COLUMN id SET DEFAULT nextval('master.amicus_curiae_allotment_direction_id_seq'::regclass);


--
-- Name: bar bar_id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.bar ALTER COLUMN bar_id SET DEFAULT nextval('master.bar_bar_id_seq'::regclass);


--
-- Name: call_listing_days id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.call_listing_days ALTER COLUMN id SET DEFAULT nextval('master.call_listing_days_id_seq'::regclass);


--
-- Name: case_remarks_head sno; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.case_remarks_head ALTER COLUMN sno SET DEFAULT nextval('master.case_remarks_head_sno_seq'::regclass);


--
-- Name: case_status_flag id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.case_status_flag ALTER COLUMN id SET DEFAULT nextval('master.case_status_flag_id_seq'::regclass);


--
-- Name: case_verify_by_sec_remark id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.case_verify_by_sec_remark ALTER COLUMN id SET DEFAULT nextval('master.case_verify_by_sec_remark_id_seq'::regclass);


--
-- Name: caselaw id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.caselaw ALTER COLUMN id SET DEFAULT nextval('master.caselaw_id_seq'::regclass);


--
-- Name: cnt_caveat id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.cnt_caveat ALTER COLUMN id SET DEFAULT nextval('master.cnt_caveat_id_seq'::regclass);


--
-- Name: cnt_diary_no id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.cnt_diary_no ALTER COLUMN id SET DEFAULT nextval('master.cnt_diary_no_id_seq'::regclass);


--
-- Name: cnt_token id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.cnt_token ALTER COLUMN id SET DEFAULT nextval('master.cnt_token_id_seq'::regclass);


--
-- Name: content_for_latestupdates id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.content_for_latestupdates ALTER COLUMN id SET DEFAULT nextval('master.content_for_latestupdates_id_seq'::regclass);


--
-- Name: copy_category id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.copy_category ALTER COLUMN id SET DEFAULT nextval('master.copy_category_id_seq'::regclass);


--
-- Name: copying_reasons_for_rejection id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.copying_reasons_for_rejection ALTER COLUMN id SET DEFAULT nextval('master.copying_reasons_for_rejection_id_seq'::regclass);


--
-- Name: copying_role id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.copying_role ALTER COLUMN id SET DEFAULT nextval('master.copying_role_id_seq'::regclass);


--
-- Name: country id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.country ALTER COLUMN id SET DEFAULT nextval('master.country_id_seq'::regclass);


--
-- Name: court_ip sno; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.court_ip ALTER COLUMN sno SET DEFAULT nextval('master.court_ip_sno_seq'::regclass);


--
-- Name: court_masters id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.court_masters ALTER COLUMN id SET DEFAULT nextval('master.court_masters_id_seq'::regclass);


--
-- Name: da_case_distribution id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.da_case_distribution ALTER COLUMN id SET DEFAULT nextval('master.da_case_distribution_id_seq'::regclass);


--
-- Name: da_case_distribution_new id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.da_case_distribution_new ALTER COLUMN id SET DEFAULT nextval('master.da_case_distribution_new_id_seq'::regclass);


--
-- Name: da_case_distribution_pilwrit id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.da_case_distribution_pilwrit ALTER COLUMN id SET DEFAULT nextval('master.da_case_distribution_pilwrit_id_seq'::regclass);


--
-- Name: da_case_distribution_tri id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.da_case_distribution_tri ALTER COLUMN id SET DEFAULT nextval('master.da_case_distribution_tri_id_seq'::regclass);


--
-- Name: da_case_distribution_tri_new id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.da_case_distribution_tri_new ALTER COLUMN id SET DEFAULT nextval('master.da_case_distribution_tri_new_id_seq'::regclass);


--
-- Name: defect_policy id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.defect_policy ALTER COLUMN id SET DEFAULT nextval('master.defect_policy_id_seq'::regclass);


--
-- Name: defect_record_paperbook id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.defect_record_paperbook ALTER COLUMN id SET DEFAULT nextval('master.defect_record_paperbook_id_seq'::regclass);


--
-- Name: drop_reason id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.drop_reason ALTER COLUMN id SET DEFAULT nextval('master.drop_reason_id_seq'::regclass);


--
-- Name: ec_pil_reference_mapping id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ec_pil_reference_mapping ALTER COLUMN id SET DEFAULT nextval('master.ec_pil_reference_mapping_id_seq'::regclass);


--
-- Name: education_type id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.education_type ALTER COLUMN id SET DEFAULT nextval('master.education_type_id_seq'::regclass);


--
-- Name: escr_users id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.escr_users ALTER COLUMN id SET DEFAULT nextval('master.escr_users_id_seq'::regclass);


--
-- Name: event_master event_code; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.event_master ALTER COLUMN event_code SET DEFAULT nextval('master.event_master_event_code_seq'::regclass);


--
-- Name: icmis_faqs id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.icmis_faqs ALTER COLUMN id SET DEFAULT nextval('master.icmis_faqs_id_seq'::regclass);


--
-- Name: id_proof_master id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.id_proof_master ALTER COLUMN id SET DEFAULT nextval('master.id_proof_master_id_seq'::regclass);


--
-- Name: judge_category id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.judge_category ALTER COLUMN id SET DEFAULT nextval('master.judge_category_id_seq'::regclass);


--
-- Name: judge_desg desgcode; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.judge_desg ALTER COLUMN desgcode SET DEFAULT nextval('master.judge_desg_desgcode_seq'::regclass);


--
-- Name: kounter id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.kounter ALTER COLUMN id SET DEFAULT nextval('master.kounter_id_seq'::regclass);


--
-- Name: law_firm law_id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.law_firm ALTER COLUMN law_id SET DEFAULT nextval('master.law_firm_law_id_seq'::regclass);


--
-- Name: law_firm_adv id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.law_firm_adv ALTER COLUMN id SET DEFAULT nextval('master.law_firm_adv_id_seq'::regclass);


--
-- Name: lc_hc_casetype lccasecode; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.lc_hc_casetype ALTER COLUMN lccasecode SET DEFAULT nextval('master.lc_hc_casetype_lccasecode_seq'::regclass);


--
-- Name: listed_info id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.listed_info ALTER COLUMN id SET DEFAULT nextval('master.listed_info_id_seq'::regclass);


--
-- Name: m_court_fee id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.m_court_fee ALTER COLUMN id SET DEFAULT nextval('master.m_court_fee_id_seq'::regclass);


--
-- Name: m_court_fee_valuation id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.m_court_fee_valuation ALTER COLUMN id SET DEFAULT nextval('master.m_court_fee_valuation_id_seq'::regclass);


--
-- Name: m_from_court id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.m_from_court ALTER COLUMN id SET DEFAULT nextval('master.m_from_court_id_seq'::regclass);


--
-- Name: m_limitation_period id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.m_limitation_period ALTER COLUMN id SET DEFAULT nextval('master.m_limitation_period_id_seq'::regclass);


--
-- Name: m_to_r_casetype_mapping id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.m_to_r_casetype_mapping ALTER COLUMN id SET DEFAULT nextval('master.m_to_r_casetype_mapping_id_seq'::regclass);


--
-- Name: main_report id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.main_report ALTER COLUMN id SET DEFAULT nextval('master.main_report_id_seq'::regclass);


--
-- Name: master_banks id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_banks ALTER COLUMN id SET DEFAULT nextval('master.master_banks_id_seq'::regclass);


--
-- Name: master_fdstatus id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_fdstatus ALTER COLUMN id SET DEFAULT nextval('master.master_fdstatus_id_seq'::regclass);


--
-- Name: master_fixedfor id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_fixedfor ALTER COLUMN id SET DEFAULT nextval('master.master_fixedfor_id_seq'::regclass);


--
-- Name: master_list_type id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_list_type ALTER COLUMN id SET DEFAULT nextval('master.master_list_type_id_seq'::regclass);


--
-- Name: master_module id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_module ALTER COLUMN id SET DEFAULT nextval('master.master_module_id_seq'::regclass);


--
-- Name: master_stakeholder_type id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_stakeholder_type ALTER COLUMN id SET DEFAULT nextval('master.master_stakeholder_type_id_seq'::regclass);


--
-- Name: media_persions id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.media_persions ALTER COLUMN id SET DEFAULT nextval('master.media_persions_id_seq'::regclass);


--
-- Name: menu id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.menu ALTER COLUMN id SET DEFAULT nextval('master.menu_id_seq1'::regclass);


--
-- Name: menu_for_latestupdates mno; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.menu_for_latestupdates ALTER COLUMN mno SET DEFAULT nextval('master.menu_for_latestupdates_mno_seq'::regclass);


--
-- Name: menu_old id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.menu_old ALTER COLUMN id SET DEFAULT nextval('master.menu_id_seq'::regclass);


--
-- Name: mn_me_per id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.mn_me_per ALTER COLUMN id SET DEFAULT nextval('master.mn_me_per_id_seq'::regclass);


--
-- Name: module_table id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.module_table ALTER COLUMN id SET DEFAULT nextval('master.module_table_id_seq'::regclass);


--
-- Name: not_before_reason res_id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.not_before_reason ALTER COLUMN res_id SET DEFAULT nextval('master.not_before_reason_res_id_seq'::regclass);


--
-- Name: notice_mapping id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.notice_mapping ALTER COLUMN id SET DEFAULT nextval('master.notice_mapping_id_seq'::regclass);


--
-- Name: objection objcode; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.objection ALTER COLUMN objcode SET DEFAULT nextval('master.objection_objcode_seq'::regclass);


--
-- Name: occupation_type id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.occupation_type ALTER COLUMN id SET DEFAULT nextval('master.occupation_type_id_seq'::regclass);


--
-- Name: office_report_master id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.office_report_master ALTER COLUMN id SET DEFAULT nextval('master.office_report_master_id_seq'::regclass);


--
-- Name: org_lower_court_judges id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.org_lower_court_judges ALTER COLUMN id SET DEFAULT nextval('master.org_lower_court_judges_id_seq'::regclass);


--
-- Name: post_envelop_master id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.post_envelop_master ALTER COLUMN id SET DEFAULT nextval('master.post_envelop_master_id_seq'::regclass);


--
-- Name: post_tariff_calc_master id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.post_tariff_calc_master ALTER COLUMN id SET DEFAULT nextval('master.post_tariff_calc_master_id_seq'::regclass);


--
-- Name: random_user id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.random_user ALTER COLUMN id SET DEFAULT nextval('master.random_user_id_seq'::regclass);


--
-- Name: random_user_hc id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.random_user_hc ALTER COLUMN id SET DEFAULT nextval('master.random_user_hc_id_seq'::regclass);


--
-- Name: ref_agency_code id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_agency_code ALTER COLUMN id SET DEFAULT nextval('master.ref_agency_code_id_seq'::regclass);


--
-- Name: ref_agency_state id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_agency_state ALTER COLUMN id SET DEFAULT nextval('master.ref_agency_state_id_seq'::regclass);


--
-- Name: ref_copying_source id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_copying_source ALTER COLUMN id SET DEFAULT nextval('master.ref_copying_source_id_seq'::regclass);


--
-- Name: ref_copying_status id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_copying_status ALTER COLUMN id SET DEFAULT nextval('master.ref_copying_status_id_seq'::regclass);


--
-- Name: ref_faster_steps id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_faster_steps ALTER COLUMN id SET DEFAULT nextval('master.ref_faster_steps_id_seq'::regclass);


--
-- Name: ref_file_movement_status id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_file_movement_status ALTER COLUMN id SET DEFAULT nextval('master.ref_file_movement_status_id_seq'::regclass);


--
-- Name: ref_items id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_items ALTER COLUMN id SET DEFAULT nextval('master.ref_items_id_seq'::regclass);


--
-- Name: ref_keyword id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_keyword ALTER COLUMN id SET DEFAULT nextval('master.ref_keyword_id_seq'::regclass);


--
-- Name: ref_letter_status id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_letter_status ALTER COLUMN id SET DEFAULT nextval('master.ref_letter_status_id_seq'::regclass);


--
-- Name: ref_pil_action_taken id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_pil_action_taken ALTER COLUMN id SET DEFAULT nextval('master.ref_pil_action_taken_id_seq'::regclass);


--
-- Name: ref_pil_category id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_pil_category ALTER COLUMN id SET DEFAULT nextval('master.ref_pil_category_id_seq'::regclass);


--
-- Name: ref_rr_hall hall_no; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_rr_hall ALTER COLUMN hall_no SET DEFAULT nextval('master.ref_rr_hall_hall_no_seq'::regclass);


--
-- Name: ref_special_category_filing id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_special_category_filing ALTER COLUMN id SET DEFAULT nextval('master.ref_special_category_filing_id_seq'::regclass);


--
-- Name: ref_state id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_state ALTER COLUMN id SET DEFAULT nextval('master.ref_state_id_seq'::regclass);


--
-- Name: role_master id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.role_master ALTER COLUMN id SET DEFAULT nextval('master.role_master_id_seq'::regclass);


--
-- Name: role_menu_mapping id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.role_menu_mapping ALTER COLUMN id SET DEFAULT nextval('master.role_menu_mapping_id_seq'::regclass);


--
-- Name: roster_bench id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.roster_bench ALTER COLUMN id SET DEFAULT nextval('master.roster_bench_id_seq'::regclass);


--
-- Name: roster_judge id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.roster_judge ALTER COLUMN id SET DEFAULT nextval('master.roster_judge_id_seq'::regclass);


--
-- Name: rr_da_case_distribution id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.rr_da_case_distribution ALTER COLUMN id SET DEFAULT nextval('master.rr_da_case_distribution_id_seq'::regclass);


--
-- Name: rr_hall_case_distribution id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.rr_hall_case_distribution ALTER COLUMN id SET DEFAULT nextval('master.rr_hall_case_distribution_id_seq'::regclass);


--
-- Name: rr_user_hall_mapping id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.rr_user_hall_mapping ALTER COLUMN id SET DEFAULT nextval('master.rr_user_hall_mapping_id_seq'::regclass);


--
-- Name: rto id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.rto ALTER COLUMN id SET DEFAULT nextval('master.rto_id_seq'::regclass);


--
-- Name: sc_working_days id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sc_working_days ALTER COLUMN id SET DEFAULT nextval('master.sc_working_days_id_seq'::regclass);


--
-- Name: sensitive_case_users id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sensitive_case_users ALTER COLUMN id SET DEFAULT nextval('master.sensitive_case_users_id_seq'::regclass);


--
-- Name: similarity_remarks id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.similarity_remarks ALTER COLUMN id SET DEFAULT nextval('master.similarity_remarks_id_seq'::regclass);


--
-- Name: single_judge_nominate id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.single_judge_nominate ALTER COLUMN id SET DEFAULT nextval('master.single_judge_nominate_id_seq'::regclass);


--
-- Name: sitting_plan_court_details id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sitting_plan_court_details ALTER COLUMN id SET DEFAULT nextval('master.sitting_plan_court_details_id_seq'::regclass);


--
-- Name: sitting_plan_details id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sitting_plan_details ALTER COLUMN id SET DEFAULT nextval('master.sitting_plan_details_id_seq'::regclass);


--
-- Name: sitting_plan_judges_details id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sitting_plan_judges_details ALTER COLUMN id SET DEFAULT nextval('master.sitting_plan_judges_details_id_seq'::regclass);


--
-- Name: sitting_plan_judges_leave_details id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sitting_plan_judges_leave_details ALTER COLUMN id SET DEFAULT nextval('master.sitting_plan_judges_leave_details_id_seq'::regclass);


--
-- Name: specific_role id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.specific_role ALTER COLUMN id SET DEFAULT nextval('master.specific_role_id_seq'::regclass);


--
-- Name: stakeholder_details id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.stakeholder_details ALTER COLUMN id SET DEFAULT nextval('master.stakeholder_details_id_seq'::regclass);


--
-- Name: state id_no; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.state ALTER COLUMN id_no SET DEFAULT nextval('master.state_id_no_seq'::regclass);


--
-- Name: sub_me_per id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sub_me_per ALTER COLUMN id SET DEFAULT nextval('master.sub_me_per_id_seq'::regclass);


--
-- Name: sub_report id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sub_report ALTER COLUMN id SET DEFAULT nextval('master.sub_report_id_seq'::regclass);


--
-- Name: sub_sub_me_per id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sub_sub_me_per ALTER COLUMN id SET DEFAULT nextval('master.sub_sub_me_per_id_seq'::regclass);


--
-- Name: sub_sub_menu su_su_menu_id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sub_sub_menu ALTER COLUMN su_su_menu_id SET DEFAULT nextval('master.sub_sub_menu_su_su_menu_id_seq'::regclass);


--
-- Name: submenu su_menu_id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.submenu ALTER COLUMN su_menu_id SET DEFAULT nextval('master.submenu_su_menu_id_seq'::regclass);


--
-- Name: t_category_master id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.t_category_master ALTER COLUMN id SET DEFAULT nextval('master.t_category_master_id_seq'::regclass);


--
-- Name: tw_max_process id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_max_process ALTER COLUMN id SET DEFAULT nextval('master.tw_max_process_id_seq'::regclass);


--
-- Name: tw_notice id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_notice ALTER COLUMN id SET DEFAULT nextval('master.tw_notice_id_seq'::regclass);


--
-- Name: tw_pf_his id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_pf_his ALTER COLUMN id SET DEFAULT nextval('master.tw_pf_his_id_seq'::regclass);


--
-- Name: tw_pin_code id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_pin_code ALTER COLUMN id SET DEFAULT nextval('master.tw_pin_code_id_seq'::regclass);


--
-- Name: tw_section id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_section ALTER COLUMN id SET DEFAULT nextval('master.tw_section_id_seq'::regclass);


--
-- Name: tw_send_to id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_send_to ALTER COLUMN id SET DEFAULT nextval('master.tw_send_to_id_seq'::regclass);


--
-- Name: tw_serve id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_serve ALTER COLUMN id SET DEFAULT nextval('master.tw_serve_id_seq'::regclass);


--
-- Name: tw_weight_or id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_weight_or ALTER COLUMN id SET DEFAULT nextval('master.tw_weight_or_id_seq'::regclass);


--
-- Name: user_d_t_map id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.user_d_t_map ALTER COLUMN id SET DEFAULT nextval('master.user_d_t_map_id_seq'::regclass);


--
-- Name: user_l_map id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.user_l_map ALTER COLUMN id SET DEFAULT nextval('master.user_l_map_id_seq'::regclass);


--
-- Name: user_l_type id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.user_l_type ALTER COLUMN id SET DEFAULT nextval('master.user_l_type_id_seq'::regclass);


--
-- Name: user_range id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.user_range ALTER COLUMN id SET DEFAULT nextval('master.user_range_id_seq'::regclass);


--
-- Name: user_role_master_mapping id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.user_role_master_mapping ALTER COLUMN id SET DEFAULT nextval('master.user_role_master_mapping_id_seq'::regclass);


--
-- Name: user_role_master_mapping_history id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.user_role_master_mapping_history ALTER COLUMN id SET DEFAULT nextval('master.user_role_master_mapping_history_id_seq'::regclass);


--
-- Name: user_sec_map id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.user_sec_map ALTER COLUMN id SET DEFAULT nextval('master.user_sec_map_id_seq'::regclass);


--
-- Name: userdept id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.userdept ALTER COLUMN id SET DEFAULT nextval('master.userdept_id_seq'::regclass);


--
-- Name: users usercode; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.users ALTER COLUMN usercode SET DEFAULT nextval('master.users_usercode_seq'::regclass);


--
-- Name: usersection id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.usersection ALTER COLUMN id SET DEFAULT nextval('master.usersection_id_seq'::regclass);


--
-- Name: usertype id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.usertype ALTER COLUMN id SET DEFAULT nextval('master.usertype_id_seq'::regclass);


--
-- Name: vernacular_languages id; Type: DEFAULT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.vernacular_languages ALTER COLUMN id SET DEFAULT nextval('master.vernacular_languages_id_seq'::regclass);


--
-- Name: ac id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ac ALTER COLUMN id SET DEFAULT nextval('public.ac_id_seq'::regclass);


--
-- Name: act_main id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.act_main ALTER COLUMN id SET DEFAULT nextval('public.act_main_id_seq'::regclass);


--
-- Name: act_main_caveat id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.act_main_caveat ALTER COLUMN id SET DEFAULT nextval('public.act_main_caveat_id_seq'::regclass);


--
-- Name: admin id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin ALTER COLUMN id SET DEFAULT nextval('public.admin_id_seq'::regclass);


--
-- Name: admin_user_permission id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin_user_permission ALTER COLUMN id SET DEFAULT nextval('public.admin_user_permission_id_seq'::regclass);


--
-- Name: advance_allocated id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advance_allocated ALTER COLUMN id SET DEFAULT nextval('public.advance_allocated_id_seq'::regclass);


--
-- Name: advance_cl_printed id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advance_cl_printed ALTER COLUMN id SET DEFAULT nextval('public.advance_cl_printed_id_seq'::regclass);


--
-- Name: advance_elimination_cl_printed id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advance_elimination_cl_printed ALTER COLUMN id SET DEFAULT nextval('public.advance_elimination_cl_printed_id_seq'::regclass);


--
-- Name: advance_single_judge_allocated id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advance_single_judge_allocated ALTER COLUMN id SET DEFAULT nextval('public.advance_single_judge_allocated_id_seq'::regclass);


--
-- Name: advanced_drop_note id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advanced_drop_note ALTER COLUMN id SET DEFAULT nextval('public.advanced_drop_note_id_seq'::regclass);


--
-- Name: advocate_requisition_request id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advocate_requisition_request ALTER COLUMN id SET DEFAULT nextval('public.advocate_requisition_request_id_seq'::regclass);


--
-- Name: allocation_trap id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.allocation_trap ALTER COLUMN id SET DEFAULT nextval('public.allocation_trap_id_seq'::regclass);


--
-- Name: amicus_curiae id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.amicus_curiae ALTER COLUMN id SET DEFAULT nextval('public.amicus_curiae_id_seq'::regclass);


--
-- Name: aor_clerk_trainee id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aor_clerk_trainee ALTER COLUMN id SET DEFAULT nextval('public.aor_clerk_trainee_id_seq'::regclass);


--
-- Name: bulk_dismissal_log id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bulk_dismissal_log ALTER COLUMN id SET DEFAULT nextval('public.bulk_dismissal_log_id_seq'::regclass);


--
-- Name: call_listing1_days id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.call_listing1_days ALTER COLUMN id SET DEFAULT nextval('public.call_listing1_days_id_seq'::regclass);


--
-- Name: case_distribution_trap id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.case_distribution_trap ALTER COLUMN id SET DEFAULT nextval('public.case_distribution_trap_id_seq'::regclass);


--
-- Name: case_info id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.case_info ALTER COLUMN id SET DEFAULT nextval('public.case_info_id_seq'::regclass);


--
-- Name: case_limit id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.case_limit ALTER COLUMN id SET DEFAULT nextval('public.case_limit_id_seq'::regclass);


--
-- Name: case_remarks_verification id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.case_remarks_verification ALTER COLUMN id SET DEFAULT nextval('public.case_remarks_verification_id_seq'::regclass);


--
-- Name: case_verify id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.case_verify ALTER COLUMN id SET DEFAULT nextval('public.case_verify_id_seq'::regclass);


--
-- Name: case_verify_rop id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.case_verify_rop ALTER COLUMN id SET DEFAULT nextval('public.case_verify_rop_id_seq'::regclass);


--
-- Name: category_allottment cat_allot_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category_allottment ALTER COLUMN cat_allot_id SET DEFAULT nextval('public.category_allottment_cat_allot_id_seq'::regclass);


--
-- Name: cause_title cause_title_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cause_title ALTER COLUMN cause_title_id SET DEFAULT nextval('public.cause_title_cause_title_id_seq'::regclass);


--
-- Name: causelist_file_movement id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.causelist_file_movement ALTER COLUMN id SET DEFAULT nextval('public.causelist_file_movement_id_seq'::regclass);


--
-- Name: causelist_file_movement_transactions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.causelist_file_movement_transactions ALTER COLUMN id SET DEFAULT nextval('public.causelist_file_movement_transactions_id_seq'::regclass);


--
-- Name: caveat_lowerct lower_court_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.caveat_lowerct ALTER COLUMN lower_court_id SET DEFAULT nextval('public.caveat_lowerct_lower_court_id_seq'::regclass);


--
-- Name: caveat_lowerct_judges id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.caveat_lowerct_judges ALTER COLUMN id SET DEFAULT nextval('public.caveat_lowerct_judges_id_seq'::regclass);


--
-- Name: chk_case id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.chk_case ALTER COLUMN id SET DEFAULT nextval('public.chk_case_id_seq'::regclass);


--
-- Name: cl_freezed id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cl_freezed ALTER COLUMN id SET DEFAULT nextval('public.cl_freezed_id_seq'::regclass);


--
-- Name: cl_gen id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cl_gen ALTER COLUMN id SET DEFAULT nextval('public.cl_gen_id_seq'::regclass);


--
-- Name: cl_printed id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cl_printed ALTER COLUMN id SET DEFAULT nextval('public.cl_printed_id_seq'::regclass);


--
-- Name: consent_through_email id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consent_through_email ALTER COLUMN id SET DEFAULT nextval('public.consent_through_email_id_seq'::regclass);


--
-- Name: copying_application_defects id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_application_defects ALTER COLUMN id SET DEFAULT nextval('public.copying_application_defects_id_seq'::regclass);


--
-- Name: copying_application_defects_org id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_application_defects_org ALTER COLUMN id SET DEFAULT nextval('public.copying_application_defects_org_id_seq'::regclass);


--
-- Name: copying_application_documents id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_application_documents ALTER COLUMN id SET DEFAULT nextval('public.copying_application_documents_id_seq'::regclass);


--
-- Name: copying_application_documents_org id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_application_documents_org ALTER COLUMN id SET DEFAULT nextval('public.copying_application_documents_org_id_seq'::regclass);


--
-- Name: copying_order_issuing_application_new id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_order_issuing_application_new ALTER COLUMN id SET DEFAULT nextval('public.copying_order_issuing_application_new_id_seq'::regclass);


--
-- Name: copying_order_issuing_application_new_org id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_order_issuing_application_new_org ALTER COLUMN id SET DEFAULT nextval('public.copying_order_issuing_application_new_org_id_seq'::regclass);


--
-- Name: copying_request_movement id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_request_movement ALTER COLUMN id SET DEFAULT nextval('public.copying_request_movement_id_seq'::regclass);


--
-- Name: copying_request_verify id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_request_verify ALTER COLUMN id SET DEFAULT nextval('public.copying_request_verify_id_seq'::regclass);


--
-- Name: copying_request_verify_documents id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_request_verify_documents ALTER COLUMN id SET DEFAULT nextval('public.copying_request_verify_documents_id_seq'::regclass);


--
-- Name: copying_request_verify_documents_log id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_request_verify_documents_log ALTER COLUMN id SET DEFAULT nextval('public.copying_request_verify_documents_log_id_seq'::regclass);


--
-- Name: copying_trap id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_trap ALTER COLUMN id SET DEFAULT nextval('public.copying_trap_id_seq'::regclass);


--
-- Name: craent id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.craent ALTER COLUMN id SET DEFAULT nextval('public.craent_id_seq'::regclass);


--
-- Name: dashboard_data id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dashboard_data ALTER COLUMN id SET DEFAULT nextval('public.dashboard_data_id_seq'::regclass);


--
-- Name: data_tentative_dates id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_tentative_dates ALTER COLUMN id SET DEFAULT nextval('public.data_tentative_dates_id_seq'::regclass);


--
-- Name: defect_case_list_26032019 id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.defect_case_list_26032019 ALTER COLUMN id SET DEFAULT nextval('public.defect_case_list_26032019_id_seq'::regclass);


--
-- Name: defective_chamber_listing id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.defective_chamber_listing ALTER COLUMN id SET DEFAULT nextval('public.defective_chamber_listing_id_seq'::regclass);


--
-- Name: defects_notified_mails id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.defects_notified_mails ALTER COLUMN id SET DEFAULT nextval('public.defects_notified_mails_id_seq'::regclass);


--
-- Name: defects_verification id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.defects_verification ALTER COLUMN id SET DEFAULT nextval('public.defects_verification_id_seq'::regclass);


--
-- Name: defects_verification_history id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.defects_verification_history ALTER COLUMN id SET DEFAULT nextval('public.defects_verification_history_id_seq'::regclass);


--
-- Name: diary_copy_set id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.diary_copy_set ALTER COLUMN id SET DEFAULT nextval('public.diary_copy_set_id_seq'::regclass);


--
-- Name: diary_movement id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.diary_movement ALTER COLUMN id SET DEFAULT nextval('public.diary_movement_id_seq'::regclass);


--
-- Name: digital_certification_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.digital_certification_details ALTER COLUMN id SET DEFAULT nextval('public.digital_certification_details_id_seq'::regclass);


--
-- Name: docdetails docd_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.docdetails ALTER COLUMN docd_id SET DEFAULT nextval('public.docdetails_docd_id_seq'::regclass);


--
-- Name: docdetails_uploaded_documents id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.docdetails_uploaded_documents ALTER COLUMN id SET DEFAULT nextval('public.docdetails_uploaded_documents_id_seq'::regclass);


--
-- Name: docdetails_uploaded_documents_log id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.docdetails_uploaded_documents_log ALTER COLUMN id SET DEFAULT nextval('public.docdetails_uploaded_documents_log_id_seq'::regclass);


--
-- Name: drop_note id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.drop_note ALTER COLUMN id SET DEFAULT nextval('public.drop_note_id_seq'::regclass);


--
-- Name: ec_forward_letter_images id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_forward_letter_images ALTER COLUMN id SET DEFAULT nextval('public.ec_forward_letter_images_id_seq'::regclass);


--
-- Name: ec_keyword id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_keyword ALTER COLUMN id SET DEFAULT nextval('public.ec_keyword_id_seq'::regclass);


--
-- Name: ec_pil id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_pil ALTER COLUMN id SET DEFAULT nextval('public.ec_pil_id_seq'::regclass);


--
-- Name: ec_pil_group_file id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_pil_group_file ALTER COLUMN id SET DEFAULT nextval('public.ec_pil_group_file_id_seq'::regclass);


--
-- Name: ec_postal_dispatch id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_postal_dispatch ALTER COLUMN id SET DEFAULT nextval('public.ec_postal_dispatch_id_seq'::regclass);


--
-- Name: ec_postal_dispatch_connected_letters id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_postal_dispatch_connected_letters ALTER COLUMN id SET DEFAULT nextval('public.ec_postal_dispatch_connected_letters_id_seq'::regclass);


--
-- Name: ec_postal_dispatch_transactions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_postal_dispatch_transactions ALTER COLUMN id SET DEFAULT nextval('public.ec_postal_dispatch_transactions_id_seq'::regclass);


--
-- Name: ec_postal_received id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_postal_received ALTER COLUMN id SET DEFAULT nextval('public.ec_postal_received_id_seq'::regclass);


--
-- Name: ec_postal_transactions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_postal_transactions ALTER COLUMN id SET DEFAULT nextval('public.ec_postal_transactions_id_seq'::regclass);


--
-- Name: ec_postal_user_initiated_letter id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_postal_user_initiated_letter ALTER COLUMN id SET DEFAULT nextval('public.ec_postal_user_initiated_letter_id_seq'::regclass);


--
-- Name: efiled_cases id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.efiled_cases ALTER COLUMN id SET DEFAULT nextval('public.efiled_cases_id_seq'::regclass);


--
-- Name: efiled_cases_transfer_status id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.efiled_cases_transfer_status ALTER COLUMN id SET DEFAULT nextval('public.efiled_cases_transfer_status_id_seq'::regclass);


--
-- Name: efiled_docs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.efiled_docs ALTER COLUMN id SET DEFAULT nextval('public.efiled_docs_id_seq'::regclass);


--
-- Name: efiled_pdfs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.efiled_pdfs ALTER COLUMN id SET DEFAULT nextval('public.efiled_pdfs_id_seq'::regclass);


--
-- Name: efiling_mails id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.efiling_mails ALTER COLUMN id SET DEFAULT nextval('public.efiling_mails_id_seq'::regclass);


--
-- Name: elimination id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.elimination ALTER COLUMN id SET DEFAULT nextval('public.elimination_id_seq'::regclass);


--
-- Name: faster_cases id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.faster_cases ALTER COLUMN id SET DEFAULT nextval('public.faster_cases_id_seq'::regclass);


--
-- Name: faster_communication_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.faster_communication_details ALTER COLUMN id SET DEFAULT nextval('public.faster_communication_details_id_seq'::regclass);


--
-- Name: faster_opted id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.faster_opted ALTER COLUMN id SET DEFAULT nextval('public.faster_opted_id_seq'::regclass);


--
-- Name: faster_shared_document_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.faster_shared_document_details ALTER COLUMN id SET DEFAULT nextval('public.faster_shared_document_details_id_seq'::regclass);


--
-- Name: faster_transactions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.faster_transactions ALTER COLUMN id SET DEFAULT nextval('public.faster_transactions_id_seq'::regclass);


--
-- Name: fdr_records id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fdr_records ALTER COLUMN id SET DEFAULT nextval('public.fdr_records_id_seq'::regclass);


--
-- Name: fil_trap uid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fil_trap ALTER COLUMN uid SET DEFAULT nextval('public.fil_trap_uid_seq'::regclass);


--
-- Name: fil_trap_his uid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fil_trap_his ALTER COLUMN uid SET DEFAULT nextval('public.fil_trap_his_uid_seq'::regclass);


--
-- Name: fil_trap_refil_users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fil_trap_refil_users ALTER COLUMN id SET DEFAULT nextval('public.fil_trap_refil_users_id_seq'::regclass);


--
-- Name: fil_trap_seq id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fil_trap_seq ALTER COLUMN id SET DEFAULT nextval('public.fil_trap_seq_id_seq'::regclass);


--
-- Name: fil_trap_users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fil_trap_users ALTER COLUMN id SET DEFAULT nextval('public.fil_trap_users_id_seq'::regclass);


--
-- Name: filing_remark id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.filing_remark ALTER COLUMN id SET DEFAULT nextval('public.filing_remark_id_seq'::regclass);


--
-- Name: filing_stats id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.filing_stats ALTER COLUMN id SET DEFAULT nextval('public.filing_stats_id_seq'::regclass);


--
-- Name: final_elimination_cl_printed id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.final_elimination_cl_printed ALTER COLUMN id SET DEFAULT nextval('public.final_elimination_cl_printed_id_seq'::regclass);


--
-- Name: headfooter hf_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.headfooter ALTER COLUMN hf_id SET DEFAULT nextval('public.headfooter_hf_id_seq'::regclass);


--
-- Name: hybrid_physical_hearing_consent id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hybrid_physical_hearing_consent ALTER COLUMN id SET DEFAULT nextval('public.hybrid_physical_hearing_consent_id_seq'::regclass);


--
-- Name: hybrid_physical_hearing_consent_freeze id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hybrid_physical_hearing_consent_freeze ALTER COLUMN id SET DEFAULT nextval('public.hybrid_physical_hearing_consent_freeze_id_seq'::regclass);


--
-- Name: idp_stats id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.idp_stats ALTER COLUMN id SET DEFAULT nextval('public.idp_stats_id_seq'::regclass);


--
-- Name: indexing ind_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.indexing ALTER COLUMN ind_id SET DEFAULT nextval('public.indexing_ind_id_seq'::regclass);


--
-- Name: jail_petition_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jail_petition_details ALTER COLUMN id SET DEFAULT nextval('public.jail_petition_details_id_seq'::regclass);


--
-- Name: judge_group id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.judge_group ALTER COLUMN id SET DEFAULT nextval('public.judge_group_id_seq'::regclass);


--
-- Name: judgment_summary id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.judgment_summary ALTER COLUMN id SET DEFAULT nextval('public.judgment_summary_id_seq'::regclass);


--
-- Name: jumped_filno id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jumped_filno ALTER COLUMN id SET DEFAULT nextval('public.jumped_filno_id_seq'::regclass);


--
-- Name: law_points id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.law_points ALTER COLUMN id SET DEFAULT nextval('public.law_points_id_seq'::regclass);


--
-- Name: lct_record_dis_rec id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lct_record_dis_rec ALTER COLUMN id SET DEFAULT nextval('public.lct_record_dis_rec_id_seq'::regclass);


--
-- Name: linked_cases id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.linked_cases ALTER COLUMN id SET DEFAULT nextval('public.linked_cases_id_seq'::regclass);


--
-- Name: loose_block id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.loose_block ALTER COLUMN id SET DEFAULT nextval('public.loose_block_id_seq'::regclass);


--
-- Name: lowerct lower_court_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lowerct ALTER COLUMN lower_court_id SET DEFAULT nextval('public.lowerct_lower_court_id_seq'::regclass);


--
-- Name: lowerct_judges id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lowerct_judges ALTER COLUMN id SET DEFAULT nextval('public.lowerct_judges_id_seq'::regclass);


--
-- Name: main_casetype_history id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.main_casetype_history ALTER COLUMN id SET DEFAULT nextval('public.main_casetype_history_id_seq'::regclass);


--
-- Name: mark_all_for_hc id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mark_all_for_hc ALTER COLUMN id SET DEFAULT nextval('public.mark_all_for_hc_id_seq'::regclass);


--
-- Name: mark_all_for_scrutiny id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mark_all_for_scrutiny ALTER COLUMN id SET DEFAULT nextval('public.mark_all_for_scrutiny_id_seq'::regclass);


--
-- Name: matters_with_wrong_section id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.matters_with_wrong_section ALTER COLUMN id SET DEFAULT nextval('public.matters_with_wrong_section_id_seq'::regclass);


--
-- Name: mention_memo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mention_memo ALTER COLUMN id SET DEFAULT nextval('public.mention_memo_id_seq'::regclass);


--
-- Name: mobile_numbers_wa id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mobile_numbers_wa ALTER COLUMN id SET DEFAULT nextval('public.mobile_numbers_wa_id_seq'::regclass);


--
-- Name: msg id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.msg ALTER COLUMN id SET DEFAULT nextval('public.msg_id_seq'::regclass);


--
-- Name: mul_category mul_category_idd; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mul_category ALTER COLUMN mul_category_idd SET DEFAULT nextval('public.mul_category_mul_category_idd_seq'::regclass);


--
-- Name: mul_category_caveat mul_category_idd; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mul_category_caveat ALTER COLUMN mul_category_idd SET DEFAULT nextval('public.mul_category_caveat_mul_category_idd_seq'::regclass);


--
-- Name: neutral_citation id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.neutral_citation ALTER COLUMN id SET DEFAULT nextval('public.neutral_citation_id_seq'::regclass);


--
-- Name: neutral_citation_01072023 id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.neutral_citation_01072023 ALTER COLUMN id SET DEFAULT nextval('public.neutral_citation_01072023_id_seq'::regclass);


--
-- Name: neutral_citation_06072023 id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.neutral_citation_06072023 ALTER COLUMN id SET DEFAULT nextval('public.neutral_citation_06072023_id_seq'::regclass);


--
-- Name: neutral_citation_24042023 id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.neutral_citation_24042023 ALTER COLUMN id SET DEFAULT nextval('public.neutral_citation_24042023_id_seq'::regclass);


--
-- Name: neutral_citation_deleted id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.neutral_citation_deleted ALTER COLUMN id SET DEFAULT nextval('public.neutral_citation_deleted_id_seq'::regclass);


--
-- Name: new_subject_category_updation id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.new_subject_category_updation ALTER COLUMN id SET DEFAULT nextval('public.new_subject_category_updation_id_seq'::regclass);


--
-- Name: njdg_cino id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.njdg_cino ALTER COLUMN id SET DEFAULT nextval('public.njdg_cino_id_seq'::regclass);


--
-- Name: njdg_stats id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.njdg_stats ALTER COLUMN id SET DEFAULT nextval('public.njdg_stats_id_seq'::regclass);


--
-- Name: njrs_mails id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.njrs_mails ALTER COLUMN id SET DEFAULT nextval('public.njrs_mails_id_seq'::regclass);


--
-- Name: not_before id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.not_before ALTER COLUMN id SET DEFAULT nextval('public.not_before_id_seq'::regclass);


--
-- Name: not_before_his id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.not_before_his ALTER COLUMN id SET DEFAULT nextval('public.not_before_his_id_seq'::regclass);


--
-- Name: obj_save id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.obj_save ALTER COLUMN id SET DEFAULT nextval('public.obj_save_id_seq'::regclass);


--
-- Name: obj_save_his id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.obj_save_his ALTER COLUMN id SET DEFAULT nextval('public.obj_save_his_id_seq'::regclass);


--
-- Name: office_report_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.office_report_details ALTER COLUMN id SET DEFAULT nextval('public.office_report_details_id_seq'::regclass);


--
-- Name: or_gist id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.or_gist ALTER COLUMN id SET DEFAULT nextval('public.or_gist_id_seq'::regclass);


--
-- Name: order_type_changed_log id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.order_type_changed_log ALTER COLUMN id SET DEFAULT nextval('public.order_type_changed_log_id_seq'::regclass);


--
-- Name: ordernet id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordernet ALTER COLUMN id SET DEFAULT nextval('public.ordernet_id_seq'::regclass);


--
-- Name: ordernet_deleted id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordernet_deleted ALTER COLUMN id SET DEFAULT nextval('public.ordernet_deleted_id_seq'::regclass);


--
-- Name: ordernet_org id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordernet_org ALTER COLUMN id SET DEFAULT nextval('public.ordernet_org_id_seq'::regclass);


--
-- Name: original_records_file id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.original_records_file ALTER COLUMN id SET DEFAULT nextval('public.original_records_file_id_seq'::regclass);


--
-- Name: other_category id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.other_category ALTER COLUMN id SET DEFAULT nextval('public.other_category_id_seq'::regclass);


--
-- Name: otp_based_login_history id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.otp_based_login_history ALTER COLUMN id SET DEFAULT nextval('public.otp_based_login_history_id_seq'::regclass);


--
-- Name: otp_sent_detail id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.otp_sent_detail ALTER COLUMN id SET DEFAULT nextval('public.otp_sent_detail_id_seq'::regclass);


--
-- Name: pap_book id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pap_book ALTER COLUMN id SET DEFAULT nextval('public.pap_book_id_seq'::regclass);


--
-- Name: paper_book_sms_log id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.paper_book_sms_log ALTER COLUMN id SET DEFAULT nextval('public.paper_book_sms_log_id_seq'::regclass);


--
-- Name: party auto_generated_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.party ALTER COLUMN auto_generated_id SET DEFAULT nextval('public.party_auto_generated_id_seq'::regclass);


--
-- Name: party_additional_address id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.party_additional_address ALTER COLUMN id SET DEFAULT nextval('public.party_additional_address_id_seq'::regclass);


--
-- Name: party_lowercourt id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.party_lowercourt ALTER COLUMN id SET DEFAULT nextval('public.party_lowercourt_id_seq'::regclass);


--
-- Name: party_order id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.party_order ALTER COLUMN id SET DEFAULT nextval('public.party_order_id_seq'::regclass);


--
-- Name: pendency_report id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pendency_report ALTER COLUMN id SET DEFAULT nextval('public.pendency_report_id_seq'::regclass);


--
-- Name: physical_hearing_advocate_consent id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.physical_hearing_advocate_consent ALTER COLUMN id SET DEFAULT nextval('public.physical_hearing_advocate_consent_id_seq'::regclass);


--
-- Name: physical_hearing_consent_required id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.physical_hearing_consent_required ALTER COLUMN id SET DEFAULT nextval('public.physical_hearing_consent_required_id_seq'::regclass);


--
-- Name: physical_hearing_consent_required_log id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.physical_hearing_consent_required_log ALTER COLUMN id SET DEFAULT nextval('public.physical_hearing_consent_required_log_id_seq'::regclass);


--
-- Name: post_bar_code_mapping id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.post_bar_code_mapping ALTER COLUMN id SET DEFAULT nextval('public.post_bar_code_mapping_id_seq'::regclass);


--
-- Name: post_envelope_movement id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.post_envelope_movement ALTER COLUMN id SET DEFAULT nextval('public.post_envelope_movement_id_seq'::regclass);


--
-- Name: proceedings id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proceedings ALTER COLUMN id SET DEFAULT nextval('public.proceedings_id_seq'::regclass);


--
-- Name: record_keeping id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.record_keeping ALTER COLUMN id SET DEFAULT nextval('public.record_keeping_id_seq'::regclass);


--
-- Name: record_room_mails id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.record_room_mails ALTER COLUMN id SET DEFAULT nextval('public.record_room_mails_id_seq'::regclass);


--
-- Name: ref_keyword id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ref_keyword ALTER COLUMN id SET DEFAULT nextval('public.ref_keyword_id_seq'::regclass);


--
-- Name: refiled_old_efiling_case_efiled_docs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.refiled_old_efiling_case_efiled_docs ALTER COLUMN id SET DEFAULT nextval('public.refiled_old_efiling_case_efiled_docs_id_seq'::regclass);


--
-- Name: registered_cases id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registered_cases ALTER COLUMN id SET DEFAULT nextval('public.registered_cases_id_seq'::regclass);


--
-- Name: registration_track id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registration_track ALTER COLUMN id SET DEFAULT nextval('public.registration_track_id_seq'::regclass);


--
-- Name: relied_details relied_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.relied_details ALTER COLUMN relied_id SET DEFAULT nextval('public.relied_details_relied_id_seq'::regclass);


--
-- Name: renewed_caveat id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.renewed_caveat ALTER COLUMN id SET DEFAULT nextval('public.renewed_caveat_id_seq'::regclass);


--
-- Name: requistion_upload id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.requistion_upload ALTER COLUMN id SET DEFAULT nextval('public.requistion_upload_id_seq'::regclass);


--
-- Name: sc_working_days_23052019 id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sc_working_days_23052019 ALTER COLUMN id SET DEFAULT nextval('public.sc_working_days_23052019_id_seq'::regclass);


--
-- Name: scan_movement id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.scan_movement ALTER COLUMN id SET DEFAULT nextval('public.scan_movement_id_seq'::regclass);


--
-- Name: scan_movement_history id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.scan_movement_history ALTER COLUMN id SET DEFAULT nextval('public.scan_movement_history_id_seq'::regclass);


--
-- Name: sclsc_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sclsc_details ALTER COLUMN id SET DEFAULT nextval('public.sclsc_details_id_seq'::regclass);


--
-- Name: scordermain id_dn; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.scordermain ALTER COLUMN id_dn SET DEFAULT nextval('public.scordermain_id_dn_seq'::regclass);


--
-- Name: section_id_change id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.section_id_change ALTER COLUMN id SET DEFAULT nextval('public.section_id_change_id_seq'::regclass);


--
-- Name: sensitive_cases id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sensitive_cases ALTER COLUMN id SET DEFAULT nextval('public.sensitive_cases_id_seq'::regclass);


--
-- Name: sentence_period id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sentence_period ALTER COLUMN id SET DEFAULT nextval('public.sentence_period_id_seq'::regclass);


--
-- Name: sentence_undergone id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sentence_undergone ALTER COLUMN id SET DEFAULT nextval('public.sentence_undergone_id_seq'::regclass);


--
-- Name: sign_document id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sign_document ALTER COLUMN id SET DEFAULT nextval('public.sign_document_id_seq'::regclass);


--
-- Name: similarity_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.similarity_details ALTER COLUMN id SET DEFAULT nextval('public.similarity_details_id_seq'::regclass);


--
-- Name: similarity_details_history id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.similarity_details_history ALTER COLUMN id SET DEFAULT nextval('public.similarity_details_history_id_seq'::regclass);


--
-- Name: single_judge_advance_cl_printed id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.single_judge_advance_cl_printed ALTER COLUMN id SET DEFAULT nextval('public.single_judge_advance_cl_printed_id_seq'::regclass);


--
-- Name: single_judge_advanced_drop_note id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.single_judge_advanced_drop_note ALTER COLUMN id SET DEFAULT nextval('public.single_judge_advanced_drop_note_id_seq'::regclass);


--
-- Name: sms_pool id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sms_pool ALTER COLUMN id SET DEFAULT nextval('public.sms_pool_id_seq'::regclass);


--
-- Name: sms_weekly id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sms_weekly ALTER COLUMN id SET DEFAULT nextval('public.sms_weekly_id_seq'::regclass);


--
-- Name: special_category_filing id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.special_category_filing ALTER COLUMN id SET DEFAULT nextval('public.special_category_filing_id_seq'::regclass);


--
-- Name: tbl_court_requisition id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tbl_court_requisition ALTER COLUMN id SET DEFAULT nextval('public.tbl_court_requisition_id_seq'::regclass);


--
-- Name: tempo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tempo ALTER COLUMN id SET DEFAULT nextval('public.tempo_id_seq'::regclass);


--
-- Name: tempo_deleted id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tempo_deleted ALTER COLUMN id SET DEFAULT nextval('public.tempo_deleted_id_seq'::regclass);


--
-- Name: transactions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions ALTER COLUMN id SET DEFAULT nextval('public.transactions_id_seq'::regclass);


--
-- Name: transcribed_arguments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transcribed_arguments ALTER COLUMN id SET DEFAULT nextval('public.transcribed_arguments_id_seq'::regclass);


--
-- Name: transfer_to_details transfer_to_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transfer_to_details ALTER COLUMN transfer_to_id SET DEFAULT nextval('public.transfer_to_details_transfer_to_id_seq'::regclass);


--
-- Name: tw_comp_not id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tw_comp_not ALTER COLUMN id SET DEFAULT nextval('public.tw_comp_not_id_seq'::regclass);


--
-- Name: tw_comp_not_history id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tw_comp_not_history ALTER COLUMN id SET DEFAULT nextval('public.tw_comp_not_history_id_seq'::regclass);


--
-- Name: tw_not_pen_sta id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tw_not_pen_sta ALTER COLUMN id SET DEFAULT nextval('public.tw_not_pen_sta_id_seq'::regclass);


--
-- Name: tw_o_r id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tw_o_r ALTER COLUMN id SET DEFAULT nextval('public.tw_o_r_id_seq'::regclass);


--
-- Name: tw_pro_desc id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tw_pro_desc ALTER COLUMN id SET DEFAULT nextval('public.tw_pro_desc_id_seq'::regclass);


--
-- Name: tw_tal_del id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tw_tal_del ALTER COLUMN id SET DEFAULT nextval('public.tw_tal_del_id_seq'::regclass);


--
-- Name: update_heardt_reason id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.update_heardt_reason ALTER COLUMN id SET DEFAULT nextval('public.update_heardt_reason_id_seq'::regclass);


--
-- Name: users_22092000 usercode; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_22092000 ALTER COLUMN usercode SET DEFAULT nextval('public.users_22092000_usercode_seq'::regclass);


--
-- Name: users_dump usercode; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_dump ALTER COLUMN usercode SET DEFAULT nextval('public.users_dump_usercode_seq'::regclass);


--
-- Name: vacation_advance_list_advocate_2018 id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vacation_advance_list_advocate_2018 ALTER COLUMN id SET DEFAULT nextval('public.vacation_advance_list_advocate_2018_id_seq'::regclass);


--
-- Name: vacation_advance_list_advocate_2023_backup id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vacation_advance_list_advocate_2023_backup ALTER COLUMN id SET DEFAULT nextval('public.vacation_advance_list_advocate_2023_backup_id_seq'::regclass);


--
-- Name: vacation_advance_list_advocate_old id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vacation_advance_list_advocate_old ALTER COLUMN id SET DEFAULT nextval('public.vacation_advance_list_advocate_old_id_seq'::regclass);


--
-- Name: vacation_advance_list_old id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vacation_advance_list_old ALTER COLUMN id SET DEFAULT nextval('public.vacation_advance_list_old_id_seq'::regclass);


--
-- Name: vc_room_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vc_room_details ALTER COLUMN id SET DEFAULT nextval('public.vc_room_details_id_seq'::regclass);


--
-- Name: vc_stats id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vc_stats ALTER COLUMN id SET DEFAULT nextval('public.vc_stats_id_seq'::regclass);


--
-- Name: vc_webcast_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vc_webcast_details ALTER COLUMN id SET DEFAULT nextval('public.vc_webcast_details_id_seq'::regclass);


--
-- Name: vc_webcast_details_temp id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vc_webcast_details_temp ALTER COLUMN id SET DEFAULT nextval('public.vc_webcast_details_temp_id_seq'::regclass);


--
-- Name: vc_webcast_history id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vc_webcast_history ALTER COLUMN id SET DEFAULT nextval('public.vc_webcast_history_id_seq'::regclass);


--
-- Name: vernacular_orders_judgments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vernacular_orders_judgments ALTER COLUMN id SET DEFAULT nextval('public.vernacular_orders_judgments_id_seq'::regclass);


--
-- Name: virtual_justice_clock id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.virtual_justice_clock ALTER COLUMN id SET DEFAULT nextval('public.virtual_justice_clock_id_seq'::regclass);


--
-- Name: virtual_justice_clock_casetype id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.virtual_justice_clock_casetype ALTER COLUMN id SET DEFAULT nextval('public.virtual_justice_clock_casetype_id_seq'::regclass);


--
-- Name: virtual_justice_clock_main_subject_category id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.virtual_justice_clock_main_subject_category ALTER COLUMN id SET DEFAULT nextval('public.virtual_justice_clock_main_subject_category_id_seq'::regclass);


--
-- Name: virtual_justice_clock_scrutiny id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.virtual_justice_clock_scrutiny ALTER COLUMN id SET DEFAULT nextval('public.virtual_justice_clock_scrutiny_id_seq'::regclass);


--
-- Name: weekly_list id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.weekly_list ALTER COLUMN id SET DEFAULT nextval('public.weekly_list_id_seq'::regclass);


--
-- Name: whatsapp_pool id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.whatsapp_pool ALTER COLUMN id SET DEFAULT nextval('public.whatsapp_pool_id_seq'::regclass);


--
-- Name: act_master idx_8264779_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.act_master
    ADD CONSTRAINT idx_8264779_primary PRIMARY KEY (id);


--
-- Name: admin_icmis_usertype_map idx_8264797_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.admin_icmis_usertype_map
    ADD CONSTRAINT idx_8264797_primary PRIMARY KEY (id);


--
-- Name: amicus_curiae_allotment_direction idx_8264897_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.amicus_curiae_allotment_direction
    ADD CONSTRAINT idx_8264897_primary PRIMARY KEY (id);


--
-- Name: authority idx_8264907_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.authority
    ADD CONSTRAINT idx_8264907_primary PRIMARY KEY (authcode);


--
-- Name: bar idx_8264926_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.bar
    ADD CONSTRAINT idx_8264926_primary PRIMARY KEY (bar_id);


--
-- Name: call_listing_days idx_8264962_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.call_listing_days
    ADD CONSTRAINT idx_8264962_primary PRIMARY KEY (id);


--
-- Name: caselaw idx_8264968_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.caselaw
    ADD CONSTRAINT idx_8264968_primary PRIMARY KEY (id);


--
-- Name: case_remarks_head idx_8265015_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.case_remarks_head
    ADD CONSTRAINT idx_8265015_primary PRIMARY KEY (sno);


--
-- Name: case_status_flag idx_8265047_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.case_status_flag
    ADD CONSTRAINT idx_8265047_primary PRIMARY KEY (id);


--
-- Name: case_verify_by_sec_remark idx_8265064_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.case_verify_by_sec_remark
    ADD CONSTRAINT idx_8265064_primary PRIMARY KEY (id);


--
-- Name: cat_jud_ratio idx_8265080_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.cat_jud_ratio
    ADD CONSTRAINT idx_8265080_primary PRIMARY KEY (cat_id, judge, next_dt);


--
-- Name: cnt_caveat idx_8265200_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.cnt_caveat
    ADD CONSTRAINT idx_8265200_primary PRIMARY KEY (id);


--
-- Name: cnt_diary_no idx_8265205_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.cnt_diary_no
    ADD CONSTRAINT idx_8265205_primary PRIMARY KEY (id);


--
-- Name: cnt_token idx_8265210_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.cnt_token
    ADD CONSTRAINT idx_8265210_primary PRIMARY KEY (id);


--
-- Name: content_for_latestupdates idx_8265234_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.content_for_latestupdates
    ADD CONSTRAINT idx_8265234_primary PRIMARY KEY (id);


--
-- Name: copying_reasons_for_rejection idx_8265303_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.copying_reasons_for_rejection
    ADD CONSTRAINT idx_8265303_primary PRIMARY KEY (id);


--
-- Name: copying_role idx_8265352_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.copying_role
    ADD CONSTRAINT idx_8265352_primary PRIMARY KEY (id);


--
-- Name: copy_category idx_8265362_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.copy_category
    ADD CONSTRAINT idx_8265362_primary PRIMARY KEY (id);


--
-- Name: country idx_8265374_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.country
    ADD CONSTRAINT idx_8265374_primary PRIMARY KEY (id);


--
-- Name: court_ip idx_8265380_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.court_ip
    ADD CONSTRAINT idx_8265380_primary PRIMARY KEY (sno);


--
-- Name: court_masters idx_8265391_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.court_masters
    ADD CONSTRAINT idx_8265391_primary PRIMARY KEY (id);


--
-- Name: da_case_distribution idx_8265443_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.da_case_distribution
    ADD CONSTRAINT idx_8265443_primary PRIMARY KEY (id);


--
-- Name: da_case_distribution_new idx_8265450_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.da_case_distribution_new
    ADD CONSTRAINT idx_8265450_primary PRIMARY KEY (id);


--
-- Name: da_case_distribution_pilwrit idx_8265457_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.da_case_distribution_pilwrit
    ADD CONSTRAINT idx_8265457_primary PRIMARY KEY (id);


--
-- Name: da_case_distribution_tri idx_8265464_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.da_case_distribution_tri
    ADD CONSTRAINT idx_8265464_primary PRIMARY KEY (id);


--
-- Name: da_case_distribution_tri_new idx_8265473_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.da_case_distribution_tri_new
    ADD CONSTRAINT idx_8265473_primary PRIMARY KEY (id);


--
-- Name: defect_policy idx_8265512_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.defect_policy
    ADD CONSTRAINT idx_8265512_primary PRIMARY KEY (id);


--
-- Name: defect_record_paperbook idx_8265517_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.defect_record_paperbook
    ADD CONSTRAINT idx_8265517_primary PRIMARY KEY (id);


--
-- Name: delhi_district_court idx_8265521_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.delhi_district_court
    ADD CONSTRAINT idx_8265521_primary PRIMARY KEY (id);


--
-- Name: disposal idx_8265553_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.disposal
    ADD CONSTRAINT idx_8265553_primary PRIMARY KEY (dispcode);


--
-- Name: district idx_8265580_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.district
    ADD CONSTRAINT idx_8265580_primary PRIMARY KEY (dcode);


--
-- Name: drop_reason idx_8265670_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.drop_reason
    ADD CONSTRAINT idx_8265670_primary PRIMARY KEY (id);


--
-- Name: ec_pil_reference_mapping idx_8265723_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ec_pil_reference_mapping
    ADD CONSTRAINT idx_8265723_primary PRIMARY KEY (id);


--
-- Name: education_type idx_8265794_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.education_type
    ADD CONSTRAINT idx_8265794_primary PRIMARY KEY (id);


--
-- Name: emp_desg idx_8265871_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.emp_desg
    ADD CONSTRAINT idx_8265871_primary PRIMARY KEY (desgcode);


--
-- Name: emp_details_t idx_8265875_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.emp_details_t
    ADD CONSTRAINT idx_8265875_primary PRIMARY KEY (empid);


--
-- Name: escr_users idx_8265879_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.escr_users
    ADD CONSTRAINT idx_8265879_primary PRIMARY KEY (id);


--
-- Name: event_master idx_8265884_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.event_master
    ADD CONSTRAINT idx_8265884_primary PRIMARY KEY (event_code);


--
-- Name: godown_user_allocation idx_8266017_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.godown_user_allocation
    ADD CONSTRAINT idx_8266017_primary PRIMARY KEY (id);


--
-- Name: holidays idx_8266064_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.holidays
    ADD CONSTRAINT idx_8266064_primary PRIMARY KEY (hdate);


--
-- Name: icmis_faqs idx_8266114_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.icmis_faqs
    ADD CONSTRAINT idx_8266114_primary PRIMARY KEY (id);


--
-- Name: id_proof_master idx_8266128_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.id_proof_master
    ADD CONSTRAINT idx_8266128_primary PRIMARY KEY (id);


--
-- Name: judge idx_8266175_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.judge
    ADD CONSTRAINT idx_8266175_primary PRIMARY KEY (jcode);


--
-- Name: judge_category idx_8266186_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.judge_category
    ADD CONSTRAINT idx_8266186_primary PRIMARY KEY (id);


--
-- Name: judge_desg idx_8266191_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.judge_desg
    ADD CONSTRAINT idx_8266191_primary PRIMARY KEY (desgcode);


--
-- Name: kounter idx_8266237_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.kounter
    ADD CONSTRAINT idx_8266237_primary PRIMARY KEY (id);


--
-- Name: law_firm idx_8266266_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.law_firm
    ADD CONSTRAINT idx_8266266_primary PRIMARY KEY (law_id);


--
-- Name: law_firm_adv idx_8266272_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.law_firm_adv
    ADD CONSTRAINT idx_8266272_primary PRIMARY KEY (id);


--
-- Name: lc_casetype idx_8266292_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.lc_casetype
    ADD CONSTRAINT idx_8266292_primary PRIMARY KEY (lccasecode);


--
-- Name: lc_hc_casetype idx_8266299_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.lc_hc_casetype
    ADD CONSTRAINT idx_8266299_primary PRIMARY KEY (lccasecode);


--
-- Name: listed_info idx_8266321_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.listed_info
    ADD CONSTRAINT idx_8266321_primary PRIMARY KEY (id);


--
-- Name: main_report idx_8266443_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.main_report
    ADD CONSTRAINT idx_8266443_primary PRIMARY KEY (id);


--
-- Name: master_banks idx_8266466_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_banks
    ADD CONSTRAINT idx_8266466_primary PRIMARY KEY (id);


--
-- Name: master_bench idx_8266472_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_bench
    ADD CONSTRAINT idx_8266472_primary PRIMARY KEY (id);


--
-- Name: master_board_type idx_8266476_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_board_type
    ADD CONSTRAINT idx_8266476_primary PRIMARY KEY (board_id);


--
-- Name: master_case_status idx_8266480_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_case_status
    ADD CONSTRAINT idx_8266480_primary PRIMARY KEY (id);


--
-- Name: master_fdstatus idx_8266488_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_fdstatus
    ADD CONSTRAINT idx_8266488_primary PRIMARY KEY (id);


--
-- Name: master_fixedfor idx_8266495_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_fixedfor
    ADD CONSTRAINT idx_8266495_primary PRIMARY KEY (id);


--
-- Name: master_list_type idx_8266501_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_list_type
    ADD CONSTRAINT idx_8266501_primary PRIMARY KEY (id);


--
-- Name: master_main_supp idx_8266505_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_main_supp
    ADD CONSTRAINT idx_8266505_primary PRIMARY KEY (id);


--
-- Name: master_module idx_8266511_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_module
    ADD CONSTRAINT idx_8266511_primary PRIMARY KEY (id);


--
-- Name: master_stakeholder_type idx_8266517_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.master_stakeholder_type
    ADD CONSTRAINT idx_8266517_primary PRIMARY KEY (id);


--
-- Name: media_persions idx_8266539_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.media_persions
    ADD CONSTRAINT idx_8266539_primary PRIMARY KEY (id);


--
-- Name: menu_old idx_8266566_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.menu_old
    ADD CONSTRAINT idx_8266566_primary PRIMARY KEY (id);


--
-- Name: menu_for_latestupdates idx_8266577_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.menu_for_latestupdates
    ADD CONSTRAINT idx_8266577_primary PRIMARY KEY (mno);


--
-- Name: mn_me_per idx_8266583_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.mn_me_per
    ADD CONSTRAINT idx_8266583_primary PRIMARY KEY (id);


--
-- Name: module_table idx_8266601_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.module_table
    ADD CONSTRAINT idx_8266601_primary PRIMARY KEY (id);


--
-- Name: m_court_fee idx_8266628_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.m_court_fee
    ADD CONSTRAINT idx_8266628_primary PRIMARY KEY (id);


--
-- Name: m_court_fee_valuation idx_8266634_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.m_court_fee_valuation
    ADD CONSTRAINT idx_8266634_primary PRIMARY KEY (id);


--
-- Name: m_from_court idx_8266639_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.m_from_court
    ADD CONSTRAINT idx_8266639_primary PRIMARY KEY (id);


--
-- Name: m_limitation_period idx_8266645_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.m_limitation_period
    ADD CONSTRAINT idx_8266645_primary PRIMARY KEY (id);


--
-- Name: m_to_r_casetype_mapping idx_8266651_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.m_to_r_casetype_mapping
    ADD CONSTRAINT idx_8266651_primary PRIMARY KEY (id);


--
-- Name: national_code_judge idx_8266663_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.national_code_judge
    ADD CONSTRAINT idx_8266663_primary PRIMARY KEY (judge_code);


--
-- Name: national_disposal_type idx_8266668_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.national_disposal_type
    ADD CONSTRAINT idx_8266668_primary PRIMARY KEY (disp_type);


--
-- Name: national_purpose_listing_stage idx_8266671_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.national_purpose_listing_stage
    ADD CONSTRAINT idx_8266671_primary PRIMARY KEY (purpose_code);


--
-- Name: notice_mapping idx_8266794_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.notice_mapping
    ADD CONSTRAINT idx_8266794_primary PRIMARY KEY (id);


--
-- Name: not_before_reason idx_8266814_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.not_before_reason
    ADD CONSTRAINT idx_8266814_primary PRIMARY KEY (res_id);


--
-- Name: objection idx_8266832_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.objection
    ADD CONSTRAINT idx_8266832_primary PRIMARY KEY (objcode);


--
-- Name: occupation_type idx_8266858_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.occupation_type
    ADD CONSTRAINT idx_8266858_primary PRIMARY KEY (id);


--
-- Name: office_report_master idx_8266872_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.office_report_master
    ADD CONSTRAINT idx_8266872_primary PRIMARY KEY (id);


--
-- Name: org_lower_court_judges idx_8266924_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.org_lower_court_judges
    ADD CONSTRAINT idx_8266924_primary PRIMARY KEY (id);


--
-- Name: pending_type idx_8267027_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.pending_type
    ADD CONSTRAINT idx_8267027_primary PRIMARY KEY (id);


--
-- Name: police idx_8267071_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.police
    ADD CONSTRAINT idx_8267071_primary PRIMARY KEY (policestncd, cmis_state_id, cmis_district_id);


--
-- Name: post_envelop_master idx_8267094_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.post_envelop_master
    ADD CONSTRAINT idx_8267094_primary PRIMARY KEY (id);


--
-- Name: post_t idx_8267099_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.post_t
    ADD CONSTRAINT idx_8267099_primary PRIMARY KEY (post_code);


--
-- Name: post_tariff_calc_master idx_8267110_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.post_tariff_calc_master
    ADD CONSTRAINT idx_8267110_primary PRIMARY KEY (id);


--
-- Name: random_user idx_8267126_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.random_user
    ADD CONSTRAINT idx_8267126_primary PRIMARY KEY (id);


--
-- Name: random_user_hc idx_8267131_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.random_user_hc
    ADD CONSTRAINT idx_8267131_primary PRIMARY KEY (id);


--
-- Name: ref_agency_code idx_8267167_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_agency_code
    ADD CONSTRAINT idx_8267167_primary PRIMARY KEY (id);


--
-- Name: ref_agency_state idx_8267174_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_agency_state
    ADD CONSTRAINT idx_8267174_primary PRIMARY KEY (id);


--
-- Name: ref_copying_source idx_8267184_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_copying_source
    ADD CONSTRAINT idx_8267184_primary PRIMARY KEY (id);


--
-- Name: ref_copying_status idx_8267190_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_copying_status
    ADD CONSTRAINT idx_8267190_primary PRIMARY KEY (id);


--
-- Name: ref_defect_code idx_8267195_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_defect_code
    ADD CONSTRAINT idx_8267195_primary PRIMARY KEY (id);


--
-- Name: ref_faster_steps idx_8267201_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_faster_steps
    ADD CONSTRAINT idx_8267201_primary PRIMARY KEY (id);


--
-- Name: ref_file_movement_status idx_8267207_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_file_movement_status
    ADD CONSTRAINT idx_8267207_primary PRIMARY KEY (id);


--
-- Name: ref_items idx_8267212_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_items
    ADD CONSTRAINT idx_8267212_primary PRIMARY KEY (id);


--
-- Name: ref_keyword idx_8267218_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_keyword
    ADD CONSTRAINT idx_8267218_primary PRIMARY KEY (id);


--
-- Name: ref_letter_status idx_8267225_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_letter_status
    ADD CONSTRAINT idx_8267225_primary PRIMARY KEY (id);


--
-- Name: ref_order_defect idx_8267236_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_order_defect
    ADD CONSTRAINT idx_8267236_primary PRIMARY KEY (id);


--
-- Name: ref_order_type idx_8267241_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_order_type
    ADD CONSTRAINT idx_8267241_primary PRIMARY KEY (id);


--
-- Name: ref_pil_action_taken idx_8267249_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_pil_action_taken
    ADD CONSTRAINT idx_8267249_primary PRIMARY KEY (id);


--
-- Name: ref_pil_category idx_8267257_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_pil_category
    ADD CONSTRAINT idx_8267257_primary PRIMARY KEY (id);


--
-- Name: ref_postal_type idx_8267264_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_postal_type
    ADD CONSTRAINT idx_8267264_primary PRIMARY KEY (id);


--
-- Name: ref_rr_hall idx_8267268_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_rr_hall
    ADD CONSTRAINT idx_8267268_primary PRIMARY KEY (hall_no);


--
-- Name: ref_special_category_filing idx_8267274_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_special_category_filing
    ADD CONSTRAINT idx_8267274_primary PRIMARY KEY (id);


--
-- Name: ref_state idx_8267279_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.ref_state
    ADD CONSTRAINT idx_8267279_primary PRIMARY KEY (id);


--
-- Name: role_master idx_8267350_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.role_master
    ADD CONSTRAINT idx_8267350_primary PRIMARY KEY (id);


--
-- Name: role_menu_mapping idx_8267356_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.role_menu_mapping
    ADD CONSTRAINT idx_8267356_primary PRIMARY KEY (id);


--
-- Name: roster idx_8267368_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.roster
    ADD CONSTRAINT idx_8267368_primary PRIMARY KEY (id);


--
-- Name: roster_bench idx_8267375_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.roster_bench
    ADD CONSTRAINT idx_8267375_primary PRIMARY KEY (id);


--
-- Name: roster_judge idx_8267381_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.roster_judge
    ADD CONSTRAINT idx_8267381_primary PRIMARY KEY (id);


--
-- Name: rr_da_case_distribution idx_8267387_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.rr_da_case_distribution
    ADD CONSTRAINT idx_8267387_primary PRIMARY KEY (id);


--
-- Name: rr_hall_case_distribution idx_8267392_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.rr_hall_case_distribution
    ADD CONSTRAINT idx_8267392_primary PRIMARY KEY (id);


--
-- Name: rr_user_hall_mapping idx_8267399_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.rr_user_hall_mapping
    ADD CONSTRAINT idx_8267399_primary PRIMARY KEY (id);


--
-- Name: rto idx_8267405_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.rto
    ADD CONSTRAINT idx_8267405_primary PRIMARY KEY (id);


--
-- Name: sc_working_days idx_8267435_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sc_working_days
    ADD CONSTRAINT idx_8267435_primary PRIMARY KEY (id);


--
-- Name: sensitive_case_users idx_8267466_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sensitive_case_users
    ADD CONSTRAINT idx_8267466_primary PRIMARY KEY (id);


--
-- Name: similarity_remarks idx_8267520_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.similarity_remarks
    ADD CONSTRAINT idx_8267520_primary PRIMARY KEY (id);


--
-- Name: single_judge_nominate idx_8267542_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.single_judge_nominate
    ADD CONSTRAINT idx_8267542_primary PRIMARY KEY (id);


--
-- Name: sitting_plan_court_details idx_8267548_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sitting_plan_court_details
    ADD CONSTRAINT idx_8267548_primary PRIMARY KEY (id);


--
-- Name: sitting_plan_details idx_8267555_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sitting_plan_details
    ADD CONSTRAINT idx_8267555_primary PRIMARY KEY (id);


--
-- Name: sitting_plan_judges_details idx_8267566_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sitting_plan_judges_details
    ADD CONSTRAINT idx_8267566_primary PRIMARY KEY (id);


--
-- Name: sitting_plan_judges_leave_details idx_8267572_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sitting_plan_judges_leave_details
    ADD CONSTRAINT idx_8267572_primary PRIMARY KEY (id);


--
-- Name: specific_role idx_8267614_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.specific_role
    ADD CONSTRAINT idx_8267614_primary PRIMARY KEY (id);


--
-- Name: stakeholder_details idx_8267621_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.stakeholder_details
    ADD CONSTRAINT idx_8267621_primary PRIMARY KEY (id);


--
-- Name: state idx_8267633_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.state
    ADD CONSTRAINT idx_8267633_primary PRIMARY KEY (id_no);


--
-- Name: subheading idx_8267639_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.subheading
    ADD CONSTRAINT idx_8267639_primary PRIMARY KEY (stagecode);


--
-- Name: submaster idx_8267644_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.submaster
    ADD CONSTRAINT idx_8267644_primary PRIMARY KEY (id);


--
-- Name: submenu idx_8267667_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.submenu
    ADD CONSTRAINT idx_8267667_primary PRIMARY KEY (su_menu_id);


--
-- Name: sub_me_per idx_8267675_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sub_me_per
    ADD CONSTRAINT idx_8267675_primary PRIMARY KEY (id);


--
-- Name: sub_report idx_8267681_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sub_report
    ADD CONSTRAINT idx_8267681_primary PRIMARY KEY (id);


--
-- Name: sub_sub_menu idx_8267689_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sub_sub_menu
    ADD CONSTRAINT idx_8267689_primary PRIMARY KEY (su_su_menu_id);


--
-- Name: sub_sub_me_per idx_8267697_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.sub_sub_me_per
    ADD CONSTRAINT idx_8267697_primary PRIMARY KEY (id);


--
-- Name: tbl_usercode_changed idx_8267740_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tbl_usercode_changed
    ADD CONSTRAINT idx_8267740_primary PRIMARY KEY (diary_no);


--
-- Name: token_status idx_8267764_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.token_status
    ADD CONSTRAINT idx_8267764_primary PRIMARY KEY (id);


--
-- Name: tw_max_process idx_8267811_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_max_process
    ADD CONSTRAINT idx_8267811_primary PRIMARY KEY (id);


--
-- Name: tw_notice idx_8267816_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_notice
    ADD CONSTRAINT idx_8267816_primary PRIMARY KEY (id);


--
-- Name: tw_pf_his idx_8267834_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_pf_his
    ADD CONSTRAINT idx_8267834_primary PRIMARY KEY (id);


--
-- Name: tw_pin_code idx_8267839_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_pin_code
    ADD CONSTRAINT idx_8267839_primary PRIMARY KEY (id);


--
-- Name: tw_section idx_8267849_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_section
    ADD CONSTRAINT idx_8267849_primary PRIMARY KEY (id);


--
-- Name: tw_send_to idx_8267854_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_send_to
    ADD CONSTRAINT idx_8267854_primary PRIMARY KEY (id);


--
-- Name: tw_serve idx_8267860_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_serve
    ADD CONSTRAINT idx_8267860_primary PRIMARY KEY (id);


--
-- Name: tw_weight_or idx_8267875_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.tw_weight_or
    ADD CONSTRAINT idx_8267875_primary PRIMARY KEY (id);


--
-- Name: t_category_master idx_8267880_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.t_category_master
    ADD CONSTRAINT idx_8267880_primary PRIMARY KEY (id);


--
-- Name: t_doc_details idx_8267885_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.t_doc_details
    ADD CONSTRAINT idx_8267885_primary PRIMARY KEY (id);


--
-- Name: userdept idx_8267897_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.userdept
    ADD CONSTRAINT idx_8267897_primary PRIMARY KEY (id);


--
-- Name: users idx_8267903_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.users
    ADD CONSTRAINT idx_8267903_primary PRIMARY KEY (usercode);


--
-- Name: usersection idx_8267915_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.usersection
    ADD CONSTRAINT idx_8267915_primary PRIMARY KEY (id);


--
-- Name: usertype idx_8267948_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.usertype
    ADD CONSTRAINT idx_8267948_primary PRIMARY KEY (id);


--
-- Name: user_d_t_map idx_8267954_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.user_d_t_map
    ADD CONSTRAINT idx_8267954_primary PRIMARY KEY (id);


--
-- Name: user_l_type idx_8267966_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.user_l_type
    ADD CONSTRAINT idx_8267966_primary PRIMARY KEY (id);


--
-- Name: user_range idx_8267972_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.user_range
    ADD CONSTRAINT idx_8267972_primary PRIMARY KEY (id);


--
-- Name: user_role_master_mapping idx_8267978_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.user_role_master_mapping
    ADD CONSTRAINT idx_8267978_primary PRIMARY KEY (id);


--
-- Name: user_role_master_mapping_history idx_8267984_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.user_role_master_mapping_history
    ADD CONSTRAINT idx_8267984_primary PRIMARY KEY (id);


--
-- Name: user_sec_map idx_8267990_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.user_sec_map
    ADD CONSTRAINT idx_8267990_primary PRIMARY KEY (id);


--
-- Name: vernacular_languages idx_8268115_primary; Type: CONSTRAINT; Schema: master; Owner: postgres
--

ALTER TABLE ONLY master.vernacular_languages
    ADD CONSTRAINT idx_8268115_primary PRIMARY KEY (id);


--
-- Name: act_main_caveat act_main_caveat_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.act_main_caveat
    ADD CONSTRAINT act_main_caveat_pkey PRIMARY KEY (id);


--
-- Name: abr_accused idx_8264759_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.abr_accused
    ADD CONSTRAINT idx_8264759_primary PRIMARY KEY (diary_no, ord_dt, p_r, p_r_side);


--
-- Name: ac idx_8264763_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ac
    ADD CONSTRAINT idx_8264763_primary PRIMARY KEY (id);


--
-- Name: admin idx_8264788_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin
    ADD CONSTRAINT idx_8264788_primary PRIMARY KEY (id);


--
-- Name: admin_user_permission idx_8264802_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin_user_permission
    ADD CONSTRAINT idx_8264802_primary PRIMARY KEY (id);


--
-- Name: admin_user_roles idx_8264807_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.admin_user_roles
    ADD CONSTRAINT idx_8264807_primary PRIMARY KEY (role_id);


--
-- Name: advanced_drop_note idx_8264816_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advanced_drop_note
    ADD CONSTRAINT idx_8264816_primary PRIMARY KEY (id);


--
-- Name: advance_allocated idx_8264824_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advance_allocated
    ADD CONSTRAINT idx_8264824_primary PRIMARY KEY (id);


--
-- Name: advance_cl_printed idx_8264829_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advance_cl_printed
    ADD CONSTRAINT idx_8264829_primary PRIMARY KEY (id);


--
-- Name: advance_elimination_cl_printed idx_8264840_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advance_elimination_cl_printed
    ADD CONSTRAINT idx_8264840_primary PRIMARY KEY (id);


--
-- Name: advance_single_judge_allocated idx_8264847_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advance_single_judge_allocated
    ADD CONSTRAINT idx_8264847_primary PRIMARY KEY (id);


--
-- Name: advance_single_judge_allocated_log idx_8264853_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advance_single_judge_allocated_log
    ADD CONSTRAINT idx_8264853_primary PRIMARY KEY (id);


--
-- Name: advocate_requisition_request idx_8264868_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advocate_requisition_request
    ADD CONSTRAINT idx_8264868_primary PRIMARY KEY (id);


--
-- Name: allocation_trap idx_8264880_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.allocation_trap
    ADD CONSTRAINT idx_8264880_primary PRIMARY KEY (id);


--
-- Name: amicus_curiae idx_8264892_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.amicus_curiae
    ADD CONSTRAINT idx_8264892_primary PRIMARY KEY (id);


--
-- Name: aor_clerk_trainee idx_8264903_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aor_clerk_trainee
    ADD CONSTRAINT idx_8264903_primary PRIMARY KEY (id);


--
-- Name: brdrem idx_8264937_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.brdrem
    ADD CONSTRAINT idx_8264937_primary PRIMARY KEY (diary_no);


--
-- Name: bulk_dismissal_log idx_8264949_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bulk_dismissal_log
    ADD CONSTRAINT idx_8264949_primary PRIMARY KEY (id);


--
-- Name: call_listing1_days idx_8264956_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.call_listing1_days
    ADD CONSTRAINT idx_8264956_primary PRIMARY KEY (id);


--
-- Name: case_distribution_trap idx_8264993_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.case_distribution_trap
    ADD CONSTRAINT idx_8264993_primary PRIMARY KEY (id);


--
-- Name: case_info idx_8265000_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.case_info
    ADD CONSTRAINT idx_8265000_primary PRIMARY KEY (id);


--
-- Name: case_limit idx_8265007_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.case_limit
    ADD CONSTRAINT idx_8265007_primary PRIMARY KEY (id);


--
-- Name: case_remarks_verification idx_8265038_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.case_remarks_verification
    ADD CONSTRAINT idx_8265038_primary PRIMARY KEY (id);


--
-- Name: case_verify idx_8265054_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.case_verify
    ADD CONSTRAINT idx_8265054_primary PRIMARY KEY (id);


--
-- Name: case_verify_rop idx_8265069_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.case_verify_rop
    ADD CONSTRAINT idx_8265069_primary PRIMARY KEY (id);


--
-- Name: category_allottment idx_8265075_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category_allottment
    ADD CONSTRAINT idx_8265075_primary PRIMARY KEY (cat_allot_id);


--
-- Name: causelist_file_movement idx_8265084_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.causelist_file_movement
    ADD CONSTRAINT idx_8265084_primary PRIMARY KEY (id);


--
-- Name: causelist_file_movement_transactions idx_8265089_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.causelist_file_movement_transactions
    ADD CONSTRAINT idx_8265089_primary PRIMARY KEY (id);


--
-- Name: cause_title idx_8265094_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cause_title
    ADD CONSTRAINT idx_8265094_primary PRIMARY KEY (cause_title_id);


--
-- Name: caveat idx_8265099_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.caveat
    ADD CONSTRAINT idx_8265099_primary PRIMARY KEY (caveat_no);


--
-- Name: caveat_lowerct idx_8265128_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.caveat_lowerct
    ADD CONSTRAINT idx_8265128_primary PRIMARY KEY (lower_court_id);


--
-- Name: caveat_lowerct_judges idx_8265135_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.caveat_lowerct_judges
    ADD CONSTRAINT idx_8265135_primary PRIMARY KEY (id);


--
-- Name: chk_case idx_8265158_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.chk_case
    ADD CONSTRAINT idx_8265158_primary PRIMARY KEY (id);


--
-- Name: cl_freezed idx_8265164_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cl_freezed
    ADD CONSTRAINT idx_8265164_primary PRIMARY KEY (id);


--
-- Name: cl_gen idx_8265174_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cl_gen
    ADD CONSTRAINT idx_8265174_primary PRIMARY KEY (id);


--
-- Name: cl_printed idx_8265181_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cl_printed
    ADD CONSTRAINT idx_8265181_primary PRIMARY KEY (id);


--
-- Name: consent_through_email idx_8265227_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.consent_through_email
    ADD CONSTRAINT idx_8265227_primary PRIMARY KEY (id);


--
-- Name: copying_application_defects idx_8265243_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_application_defects
    ADD CONSTRAINT idx_8265243_primary PRIMARY KEY (id);


--
-- Name: copying_application_defects_org idx_8265248_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_application_defects_org
    ADD CONSTRAINT idx_8265248_primary PRIMARY KEY (id);


--
-- Name: copying_application_documents idx_8265253_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_application_documents
    ADD CONSTRAINT idx_8265253_primary PRIMARY KEY (id);


--
-- Name: copying_application_documents_org idx_8265263_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_application_documents_org
    ADD CONSTRAINT idx_8265263_primary PRIMARY KEY (id);


--
-- Name: copying_order_issuing_application_new idx_8265269_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_order_issuing_application_new
    ADD CONSTRAINT idx_8265269_primary PRIMARY KEY (copy_category, application_reg_number, application_reg_year);


--
-- Name: copying_order_issuing_application_new_org idx_8265293_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_order_issuing_application_new_org
    ADD CONSTRAINT idx_8265293_primary PRIMARY KEY (id);


--
-- Name: copying_request_movement idx_8265308_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_request_movement
    ADD CONSTRAINT idx_8265308_primary PRIMARY KEY (id);


--
-- Name: copying_request_verify idx_8265314_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_request_verify
    ADD CONSTRAINT idx_8265314_primary PRIMARY KEY (crn);


--
-- Name: copying_request_verify_documents idx_8265328_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_request_verify_documents
    ADD CONSTRAINT idx_8265328_primary PRIMARY KEY (id);


--
-- Name: copying_request_verify_documents_log idx_8265340_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_request_verify_documents_log
    ADD CONSTRAINT idx_8265340_primary PRIMARY KEY (id);


--
-- Name: copying_trap idx_8265357_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.copying_trap
    ADD CONSTRAINT idx_8265357_primary PRIMARY KEY (id);


--
-- Name: craent idx_8265398_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.craent
    ADD CONSTRAINT idx_8265398_primary PRIMARY KEY (id);


--
-- Name: dashboard_data idx_8265406_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dashboard_data
    ADD CONSTRAINT idx_8265406_primary PRIMARY KEY (id);


--
-- Name: data_tentative_dates idx_8265425_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_tentative_dates
    ADD CONSTRAINT idx_8265425_primary PRIMARY KEY (id);


--
-- Name: defective_chamber_listing idx_8265480_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.defective_chamber_listing
    ADD CONSTRAINT idx_8265480_primary PRIMARY KEY (id);


--
-- Name: defects_notified_mails idx_8265487_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.defects_notified_mails
    ADD CONSTRAINT idx_8265487_primary PRIMARY KEY (id);


--
-- Name: defects_verification idx_8265494_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.defects_verification
    ADD CONSTRAINT idx_8265494_primary PRIMARY KEY (id);


--
-- Name: defects_verification_history idx_8265500_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.defects_verification_history
    ADD CONSTRAINT idx_8265500_primary PRIMARY KEY (id);


--
-- Name: defect_case_list_26032019 idx_8265507_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.defect_case_list_26032019
    ADD CONSTRAINT idx_8265507_primary PRIMARY KEY (id);


--
-- Name: diary_copy_set idx_8265535_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.diary_copy_set
    ADD CONSTRAINT idx_8265535_primary PRIMARY KEY (id);


--
-- Name: diary_movement idx_8265540_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.diary_movement
    ADD CONSTRAINT idx_8265540_primary PRIMARY KEY (id);


--
-- Name: digital_certification_details idx_8265548_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.digital_certification_details
    ADD CONSTRAINT idx_8265548_primary PRIMARY KEY (id);


--
-- Name: dispose idx_8265559_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dispose
    ADD CONSTRAINT idx_8265559_primary PRIMARY KEY (diary_no);


--
-- Name: docdetails idx_8265587_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.docdetails
    ADD CONSTRAINT idx_8265587_primary PRIMARY KEY (docd_id);


--
-- Name: docdetails_uploaded_documents idx_8265625_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.docdetails_uploaded_documents
    ADD CONSTRAINT idx_8265625_primary PRIMARY KEY (id);


--
-- Name: docdetails_uploaded_documents_log idx_8265634_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.docdetails_uploaded_documents_log
    ADD CONSTRAINT idx_8265634_primary PRIMARY KEY (id);


--
-- Name: draft_list idx_8265657_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.draft_list
    ADD CONSTRAINT idx_8265657_primary PRIMARY KEY (diary_no, next_dt_old, conn_key, list_type, board_type);


--
-- Name: drop_note idx_8265662_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.drop_note
    ADD CONSTRAINT idx_8265662_primary PRIMARY KEY (id);


--
-- Name: ec_forward_letter_images idx_8265687_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_forward_letter_images
    ADD CONSTRAINT idx_8265687_primary PRIMARY KEY (id);


--
-- Name: ec_keyword idx_8265696_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_keyword
    ADD CONSTRAINT idx_8265696_primary PRIMARY KEY (id);


--
-- Name: ec_pil idx_8265702_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_pil
    ADD CONSTRAINT idx_8265702_primary PRIMARY KEY (id);


--
-- Name: ec_pil_group_file idx_8265710_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_pil_group_file
    ADD CONSTRAINT idx_8265710_primary PRIMARY KEY (id);


--
-- Name: ec_postal_dispatch idx_8265729_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_postal_dispatch
    ADD CONSTRAINT idx_8265729_primary PRIMARY KEY (id);


--
-- Name: ec_postal_dispatch_connected_letters idx_8265740_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_postal_dispatch_connected_letters
    ADD CONSTRAINT idx_8265740_primary PRIMARY KEY (id);


--
-- Name: ec_postal_dispatch_transactions idx_8265759_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_postal_dispatch_transactions
    ADD CONSTRAINT idx_8265759_primary PRIMARY KEY (id);


--
-- Name: ec_postal_received idx_8265764_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_postal_received
    ADD CONSTRAINT idx_8265764_primary PRIMARY KEY (id);


--
-- Name: ec_postal_received_log idx_8265772_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_postal_received_log
    ADD CONSTRAINT idx_8265772_primary PRIMARY KEY (id);


--
-- Name: ec_postal_transactions idx_8265780_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_postal_transactions
    ADD CONSTRAINT idx_8265780_primary PRIMARY KEY (id);


--
-- Name: ec_postal_user_initiated_letter idx_8265788_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ec_postal_user_initiated_letter
    ADD CONSTRAINT idx_8265788_primary PRIMARY KEY (id);


--
-- Name: efiled_cases idx_8265800_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.efiled_cases
    ADD CONSTRAINT idx_8265800_primary PRIMARY KEY (id);


--
-- Name: efiled_cases_transfer_status idx_8265811_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.efiled_cases_transfer_status
    ADD CONSTRAINT idx_8265811_primary PRIMARY KEY (id);


--
-- Name: efiled_docs idx_8265816_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.efiled_docs
    ADD CONSTRAINT idx_8265816_primary PRIMARY KEY (id);


--
-- Name: efiled_pdfs idx_8265822_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.efiled_pdfs
    ADD CONSTRAINT idx_8265822_primary PRIMARY KEY (id);


--
-- Name: efiling_mails idx_8265829_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.efiling_mails
    ADD CONSTRAINT idx_8265829_primary PRIMARY KEY (id);


--
-- Name: elimination idx_8265840_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.elimination
    ADD CONSTRAINT idx_8265840_primary PRIMARY KEY (id);


--
-- Name: email_entire_list idx_8265846_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.email_entire_list
    ADD CONSTRAINT idx_8265846_primary PRIMARY KEY (cl_date);


--
-- Name: faster_cases idx_8265889_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.faster_cases
    ADD CONSTRAINT idx_8265889_primary PRIMARY KEY (id);


--
-- Name: faster_communication_details idx_8265896_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.faster_communication_details
    ADD CONSTRAINT idx_8265896_primary PRIMARY KEY (id);


--
-- Name: faster_opted idx_8265903_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.faster_opted
    ADD CONSTRAINT idx_8265903_primary PRIMARY KEY (id);


--
-- Name: faster_shared_document_details idx_8265909_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.faster_shared_document_details
    ADD CONSTRAINT idx_8265909_primary PRIMARY KEY (id);


--
-- Name: faster_transactions idx_8265917_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.faster_transactions
    ADD CONSTRAINT idx_8265917_primary PRIMARY KEY (id);


--
-- Name: fdr_records idx_8265923_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fdr_records
    ADD CONSTRAINT idx_8265923_primary PRIMARY KEY (id);


--
-- Name: filing_remark idx_8265943_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.filing_remark
    ADD CONSTRAINT idx_8265943_primary PRIMARY KEY (id);


--
-- Name: filing_stats idx_8265950_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.filing_stats
    ADD CONSTRAINT idx_8265950_primary PRIMARY KEY (id);


--
-- Name: fil_trap idx_8265965_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fil_trap
    ADD CONSTRAINT idx_8265965_primary PRIMARY KEY (uid);


--
-- Name: fil_trap_his idx_8265974_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fil_trap_his
    ADD CONSTRAINT idx_8265974_primary PRIMARY KEY (uid);


--
-- Name: fil_trap_refil_users idx_8265983_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fil_trap_refil_users
    ADD CONSTRAINT idx_8265983_primary PRIMARY KEY (id);


--
-- Name: fil_trap_seq idx_8265988_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fil_trap_seq
    ADD CONSTRAINT idx_8265988_primary PRIMARY KEY (id);


--
-- Name: fil_trap_users idx_8265993_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fil_trap_users
    ADD CONSTRAINT idx_8265993_primary PRIMARY KEY (id);


--
-- Name: final_elimination_cl_printed idx_8265999_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.final_elimination_cl_printed
    ADD CONSTRAINT idx_8265999_primary PRIMARY KEY (id);


--
-- Name: headfooter idx_8266023_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.headfooter
    ADD CONSTRAINT idx_8266023_primary PRIMARY KEY (hf_id);


--
-- Name: heardt idx_8266029_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.heardt
    ADD CONSTRAINT idx_8266029_primary PRIMARY KEY (diary_no);


--
-- Name: heardt_webuse idx_8266047_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.heardt_webuse
    ADD CONSTRAINT idx_8266047_primary PRIMARY KEY (diary_no);


--
-- Name: hybrid_physical_hearing_consent idx_8266068_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hybrid_physical_hearing_consent
    ADD CONSTRAINT idx_8266068_primary PRIMARY KEY (id);


--
-- Name: hybrid_physical_hearing_consent_freeze idx_8266075_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hybrid_physical_hearing_consent_freeze
    ADD CONSTRAINT idx_8266075_primary PRIMARY KEY (id);


--
-- Name: idp_stats idx_8266122_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.idp_stats
    ADD CONSTRAINT idx_8266122_primary PRIMARY KEY (id);


--
-- Name: indexing idx_8266135_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.indexing
    ADD CONSTRAINT idx_8266135_primary PRIMARY KEY (ind_id);


--
-- Name: jail_petition_details idx_8266167_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jail_petition_details
    ADD CONSTRAINT idx_8266167_primary PRIMARY KEY (id);


--
-- Name: judge_group idx_8266196_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.judge_group
    ADD CONSTRAINT idx_8266196_primary PRIMARY KEY (id);


--
-- Name: judgment_summary idx_8266211_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.judgment_summary
    ADD CONSTRAINT idx_8266211_primary PRIMARY KEY (id);


--
-- Name: jumped_filno idx_8266227_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jumped_filno
    ADD CONSTRAINT idx_8266227_primary PRIMARY KEY (id);


--
-- Name: law_points idx_8266277_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.law_points
    ADD CONSTRAINT idx_8266277_primary PRIMARY KEY (id);


--
-- Name: lct_record_dis_rec idx_8266285_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lct_record_dis_rec
    ADD CONSTRAINT idx_8266285_primary PRIMARY KEY (id);


--
-- Name: linked_cases idx_8266315_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.linked_cases
    ADD CONSTRAINT idx_8266315_primary PRIMARY KEY (id);


--
-- Name: lowerct idx_8266341_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lowerct
    ADD CONSTRAINT idx_8266341_primary PRIMARY KEY (lower_court_id);


--
-- Name: lowerct_judges idx_8266354_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lowerct_judges
    ADD CONSTRAINT idx_8266354_primary PRIMARY KEY (id);


--
-- Name: main idx_8266359_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.main
    ADD CONSTRAINT idx_8266359_primary PRIMARY KEY (diary_no);


--
-- Name: main_casetype_history idx_8266396_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.main_casetype_history
    ADD CONSTRAINT idx_8266396_primary PRIMARY KEY (id);


--
-- Name: main_deleted_cases idx_8266418_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.main_deleted_cases
    ADD CONSTRAINT idx_8266418_primary PRIMARY KEY (diary_no);


--
-- Name: main_ingestion idx_8266430_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.main_ingestion
    ADD CONSTRAINT idx_8266430_primary PRIMARY KEY (diary_no);


--
-- Name: main_section_update idx_8266448_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.main_section_update
    ADD CONSTRAINT idx_8266448_primary PRIMARY KEY (diary_no);


--
-- Name: mark_all_for_hc idx_8266452_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mark_all_for_hc
    ADD CONSTRAINT idx_8266452_primary PRIMARY KEY (id);


--
-- Name: mark_all_for_scrutiny idx_8266459_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mark_all_for_scrutiny
    ADD CONSTRAINT idx_8266459_primary PRIMARY KEY (id);


--
-- Name: matters_auto_updated idx_8266528_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.matters_auto_updated
    ADD CONSTRAINT idx_8266528_primary PRIMARY KEY (main_matter);


--
-- Name: matters_with_wrong_section idx_8266534_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.matters_with_wrong_section
    ADD CONSTRAINT idx_8266534_primary PRIMARY KEY (id);


--
-- Name: mention_memo idx_8266550_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mention_memo
    ADD CONSTRAINT idx_8266550_primary PRIMARY KEY (id);


--
-- Name: mobile_numbers_wa idx_8266589_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mobile_numbers_wa
    ADD CONSTRAINT idx_8266589_primary PRIMARY KEY (id);


--
-- Name: msg idx_8266608_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.msg
    ADD CONSTRAINT idx_8266608_primary PRIMARY KEY (id);


--
-- Name: mul_category idx_8266622_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mul_category
    ADD CONSTRAINT idx_8266622_primary PRIMARY KEY (mul_category_idd);


--
-- Name: neutral_citation idx_8266677_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.neutral_citation
    ADD CONSTRAINT idx_8266677_primary PRIMARY KEY (id);


--
-- Name: neutral_citation_01072023 idx_8266685_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.neutral_citation_01072023
    ADD CONSTRAINT idx_8266685_primary PRIMARY KEY (id);


--
-- Name: neutral_citation_06072023 idx_8266693_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.neutral_citation_06072023
    ADD CONSTRAINT idx_8266693_primary PRIMARY KEY (id);


--
-- Name: neutral_citation_24042023 idx_8266701_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.neutral_citation_24042023
    ADD CONSTRAINT idx_8266701_primary PRIMARY KEY (id);


--
-- Name: neutral_citation_deleted idx_8266709_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.neutral_citation_deleted
    ADD CONSTRAINT idx_8266709_primary PRIMARY KEY (id);


--
-- Name: new_subject_category_updation idx_8266717_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.new_subject_category_updation
    ADD CONSTRAINT idx_8266717_primary PRIMARY KEY (id);


--
-- Name: njdg_act idx_8266736_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.njdg_act
    ADD CONSTRAINT idx_8266736_primary PRIMARY KEY (diary_no);


--
-- Name: njdg_cino idx_8266744_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.njdg_cino
    ADD CONSTRAINT idx_8266744_primary PRIMARY KEY (id);


--
-- Name: njdg_purpose idx_8266761_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.njdg_purpose
    ADD CONSTRAINT idx_8266761_primary PRIMARY KEY (purpose_code);


--
-- Name: njdg_stats idx_8266765_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.njdg_stats
    ADD CONSTRAINT idx_8266765_primary PRIMARY KEY (id);


--
-- Name: njdg_transaction idx_8266773_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.njdg_transaction
    ADD CONSTRAINT idx_8266773_primary PRIMARY KEY (cino);


--
-- Name: njrs_mails idx_8266787_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.njrs_mails
    ADD CONSTRAINT idx_8266787_primary PRIMARY KEY (id);


--
-- Name: not_before idx_8266799_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.not_before
    ADD CONSTRAINT idx_8266799_primary PRIMARY KEY (id);


--
-- Name: not_before_his idx_8266807_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.not_before_his
    ADD CONSTRAINT idx_8266807_primary PRIMARY KEY (id);


--
-- Name: obj_save idx_8266844_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.obj_save
    ADD CONSTRAINT idx_8266844_primary PRIMARY KEY (id);


--
-- Name: obj_save_his idx_8266853_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.obj_save_his
    ADD CONSTRAINT idx_8266853_primary PRIMARY KEY (id);


--
-- Name: office_report_details idx_8266864_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.office_report_details
    ADD CONSTRAINT idx_8266864_primary PRIMARY KEY (id);


--
-- Name: ordernet idx_8266878_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordernet
    ADD CONSTRAINT idx_8266878_primary PRIMARY KEY (id);


--
-- Name: ordernet_deleted idx_8266890_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordernet_deleted
    ADD CONSTRAINT idx_8266890_primary PRIMARY KEY (id);


--
-- Name: ordernet_org idx_8266902_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordernet_org
    ADD CONSTRAINT idx_8266902_primary PRIMARY KEY (id);


--
-- Name: order_type_changed_log idx_8266919_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.order_type_changed_log
    ADD CONSTRAINT idx_8266919_primary PRIMARY KEY (id);


--
-- Name: original_records_file idx_8266929_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.original_records_file
    ADD CONSTRAINT idx_8266929_primary PRIMARY KEY (id);


--
-- Name: or_gist idx_8266934_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.or_gist
    ADD CONSTRAINT idx_8266934_primary PRIMARY KEY (id);


--
-- Name: other_category idx_8266942_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.other_category
    ADD CONSTRAINT idx_8266942_primary PRIMARY KEY (id);


--
-- Name: otp_based_login_history idx_8266949_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.otp_based_login_history
    ADD CONSTRAINT idx_8266949_primary PRIMARY KEY (id);


--
-- Name: otp_sent_detail idx_8266956_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.otp_sent_detail
    ADD CONSTRAINT idx_8266956_primary PRIMARY KEY (id);


--
-- Name: paper_book_sms_log idx_8266966_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.paper_book_sms_log
    ADD CONSTRAINT idx_8266966_primary PRIMARY KEY (id);


--
-- Name: pap_book idx_8266973_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pap_book
    ADD CONSTRAINT idx_8266973_primary PRIMARY KEY (id);


--
-- Name: party idx_8266980_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.party
    ADD CONSTRAINT idx_8266980_primary PRIMARY KEY (auto_generated_id);


--
-- Name: party_additional_address idx_8266993_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.party_additional_address
    ADD CONSTRAINT idx_8266993_primary PRIMARY KEY (id);


--
-- Name: party_lowercourt idx_8267006_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.party_lowercourt
    ADD CONSTRAINT idx_8267006_primary PRIMARY KEY (id);


--
-- Name: party_order idx_8267012_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.party_order
    ADD CONSTRAINT idx_8267012_primary PRIMARY KEY (id);


--
-- Name: pendency_report idx_8267018_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pendency_report
    ADD CONSTRAINT idx_8267018_primary PRIMARY KEY (id);


--
-- Name: physical_hearing_advocate_consent idx_8267031_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.physical_hearing_advocate_consent
    ADD CONSTRAINT idx_8267031_primary PRIMARY KEY (id);


--
-- Name: physical_hearing_consent_required idx_8267048_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.physical_hearing_consent_required
    ADD CONSTRAINT idx_8267048_primary PRIMARY KEY (id);


--
-- Name: physical_hearing_consent_required_log idx_8267057_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.physical_hearing_consent_required_log
    ADD CONSTRAINT idx_8267057_primary PRIMARY KEY (id);


--
-- Name: physical_verify_old idx_8267068_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.physical_verify_old
    ADD CONSTRAINT idx_8267068_primary PRIMARY KEY (diary_no);


--
-- Name: post_bar_code_mapping idx_8267077_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.post_bar_code_mapping
    ADD CONSTRAINT idx_8267077_primary PRIMARY KEY (id);


--
-- Name: post_envelope_movement idx_8267088_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.post_envelope_movement
    ADD CONSTRAINT idx_8267088_primary PRIMARY KEY (id);


--
-- Name: proceedings idx_8267116_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proceedings
    ADD CONSTRAINT idx_8267116_primary PRIMARY KEY (id);


--
-- Name: recalled_deleted idx_8267135_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recalled_deleted
    ADD CONSTRAINT idx_8267135_primary PRIMARY KEY (diary_no);


--
-- Name: recalled_matters idx_8267138_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recalled_matters
    ADD CONSTRAINT idx_8267138_primary PRIMARY KEY (diary_no);


--
-- Name: recalled_matters_21122018 idx_8267141_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recalled_matters_21122018
    ADD CONSTRAINT idx_8267141_primary PRIMARY KEY (diary_no);


--
-- Name: record_keeping idx_8267145_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.record_keeping
    ADD CONSTRAINT idx_8267145_primary PRIMARY KEY (id);


--
-- Name: record_room_mails idx_8267154_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.record_room_mails
    ADD CONSTRAINT idx_8267154_primary PRIMARY KEY (id);


--
-- Name: refiled_old_efiling_case_efiled_docs idx_8267161_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.refiled_old_efiling_case_efiled_docs
    ADD CONSTRAINT idx_8267161_primary PRIMARY KEY (id);


--
-- Name: registered_cases idx_8267294_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registered_cases
    ADD CONSTRAINT idx_8267294_primary PRIMARY KEY (id);


--
-- Name: registration_track idx_8267300_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registration_track
    ADD CONSTRAINT idx_8267300_primary PRIMARY KEY (id);


--
-- Name: reg_dt0 idx_8267304_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reg_dt0
    ADD CONSTRAINT idx_8267304_primary PRIMARY KEY (diary_no);


--
-- Name: relied_details idx_8267308_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.relied_details
    ADD CONSTRAINT idx_8267308_primary PRIMARY KEY (relied_id);


--
-- Name: renewed_caveat idx_8267314_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.renewed_caveat
    ADD CONSTRAINT idx_8267314_primary PRIMARY KEY (id);


--
-- Name: requistion_upload idx_8267319_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.requistion_upload
    ADD CONSTRAINT idx_8267319_primary PRIMARY KEY (id);


--
-- Name: scan_movement idx_8267411_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.scan_movement
    ADD CONSTRAINT idx_8267411_primary PRIMARY KEY (id);


--
-- Name: scan_movement_history idx_8267416_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.scan_movement_history
    ADD CONSTRAINT idx_8267416_primary PRIMARY KEY (id);


--
-- Name: sclsc_details idx_8267421_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sclsc_details
    ADD CONSTRAINT idx_8267421_primary PRIMARY KEY (id);


--
-- Name: scordermain idx_8267427_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.scordermain
    ADD CONSTRAINT idx_8267427_primary PRIMARY KEY (id_dn);


--
-- Name: sc_working_days_23052019 idx_8267444_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sc_working_days_23052019
    ADD CONSTRAINT idx_8267444_primary PRIMARY KEY (id);


--
-- Name: section_id_change idx_8267453_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.section_id_change
    ADD CONSTRAINT idx_8267453_primary PRIMARY KEY (id);


--
-- Name: sensitive_cases idx_8267458_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sensitive_cases
    ADD CONSTRAINT idx_8267458_primary PRIMARY KEY (id);


--
-- Name: sentence_period idx_8267473_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sentence_period
    ADD CONSTRAINT idx_8267473_primary PRIMARY KEY (id);


--
-- Name: sentence_undergone idx_8267478_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sentence_undergone
    ADD CONSTRAINT idx_8267478_primary PRIMARY KEY (id);


--
-- Name: showlcd idx_8267482_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.showlcd
    ADD CONSTRAINT idx_8267482_primary PRIMARY KEY (court, cl_dt, ent_dt);


--
-- Name: sign_document idx_8267499_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sign_document
    ADD CONSTRAINT idx_8267499_primary PRIMARY KEY (id);


--
-- Name: similarity_details idx_8267506_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.similarity_details
    ADD CONSTRAINT idx_8267506_primary PRIMARY KEY (id);


--
-- Name: similarity_details_history idx_8267513_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.similarity_details_history
    ADD CONSTRAINT idx_8267513_primary PRIMARY KEY (id);


--
-- Name: single_judge_advanced_drop_note idx_8267527_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.single_judge_advanced_drop_note
    ADD CONSTRAINT idx_8267527_primary PRIMARY KEY (id);


--
-- Name: single_judge_advance_cl_printed idx_8267535_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.single_judge_advance_cl_printed
    ADD CONSTRAINT idx_8267535_primary PRIMARY KEY (id);


--
-- Name: sms_pool idx_8267591_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sms_pool
    ADD CONSTRAINT idx_8267591_primary PRIMARY KEY (id);


--
-- Name: sms_weekly idx_8267599_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sms_weekly
    ADD CONSTRAINT idx_8267599_primary PRIMARY KEY (id);


--
-- Name: special_category_filing idx_8267609_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.special_category_filing
    ADD CONSTRAINT idx_8267609_primary PRIMARY KEY (id);


--
-- Name: submaster_old idx_8267655_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.submaster_old
    ADD CONSTRAINT idx_8267655_primary PRIMARY KEY (id);


--
-- Name: tbl_court_requisition idx_8267703_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tbl_court_requisition
    ADD CONSTRAINT idx_8267703_primary PRIMARY KEY (id);


--
-- Name: tempo idx_8267748_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tempo
    ADD CONSTRAINT idx_8267748_primary PRIMARY KEY (id);


--
-- Name: tempo_deleted idx_8267753_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tempo_deleted
    ADD CONSTRAINT idx_8267753_primary PRIMARY KEY (id);


--
-- Name: temp_sclsc_cvs idx_8267757_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.temp_sclsc_cvs
    ADD CONSTRAINT idx_8267757_primary PRIMARY KEY (sno);


--
-- Name: transactions idx_8267768_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT idx_8267768_primary PRIMARY KEY (id);


--
-- Name: transcribed_arguments idx_8267776_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transcribed_arguments
    ADD CONSTRAINT idx_8267776_primary PRIMARY KEY (id);


--
-- Name: transfer_to_details idx_8267785_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transfer_to_details
    ADD CONSTRAINT idx_8267785_primary PRIMARY KEY (transfer_to_id);


--
-- Name: tw_comp_not idx_8267795_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tw_comp_not
    ADD CONSTRAINT idx_8267795_primary PRIMARY KEY (id);


--
-- Name: tw_comp_not_history idx_8267803_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tw_comp_not_history
    ADD CONSTRAINT idx_8267803_primary PRIMARY KEY (id);


--
-- Name: tw_not_pen_sta idx_8267823_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tw_not_pen_sta
    ADD CONSTRAINT idx_8267823_primary PRIMARY KEY (id);


--
-- Name: tw_o_r idx_8267828_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tw_o_r
    ADD CONSTRAINT idx_8267828_primary PRIMARY KEY (id);


--
-- Name: tw_pro_desc idx_8267844_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tw_pro_desc
    ADD CONSTRAINT idx_8267844_primary PRIMARY KEY (id);


--
-- Name: tw_tal_del idx_8267866_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tw_tal_del
    ADD CONSTRAINT idx_8267866_primary PRIMARY KEY (id);


--
-- Name: update_heardt_reason idx_8267892_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.update_heardt_reason
    ADD CONSTRAINT idx_8267892_primary PRIMARY KEY (id);


--
-- Name: users_22092000 idx_8267924_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_22092000
    ADD CONSTRAINT idx_8267924_primary PRIMARY KEY (usercode);


--
-- Name: users_dump idx_8267936_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users_dump
    ADD CONSTRAINT idx_8267936_primary PRIMARY KEY (usercode);


--
-- Name: vacation_advance_list_advocate_2018 idx_8268003_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vacation_advance_list_advocate_2018
    ADD CONSTRAINT idx_8268003_primary PRIMARY KEY (id);


--
-- Name: vacation_advance_list_advocate_2023_backup idx_8268010_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vacation_advance_list_advocate_2023_backup
    ADD CONSTRAINT idx_8268010_primary PRIMARY KEY (id);


--
-- Name: vacation_advance_list_advocate_old idx_8268025_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vacation_advance_list_advocate_old
    ADD CONSTRAINT idx_8268025_primary PRIMARY KEY (id);


--
-- Name: vacation_advance_list_old idx_8268040_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vacation_advance_list_old
    ADD CONSTRAINT idx_8268040_primary PRIMARY KEY (id);


--
-- Name: vacation_registrar_pool idx_8268053_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vacation_registrar_pool
    ADD CONSTRAINT idx_8268053_primary PRIMARY KEY (diary_no);


--
-- Name: vc_room_details idx_8268058_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vc_room_details
    ADD CONSTRAINT idx_8268058_primary PRIMARY KEY (id);


--
-- Name: vc_stats idx_8268066_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vc_stats
    ADD CONSTRAINT idx_8268066_primary PRIMARY KEY (id);


--
-- Name: vc_webcast_details idx_8268072_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vc_webcast_details
    ADD CONSTRAINT idx_8268072_primary PRIMARY KEY (id);


--
-- Name: vc_webcast_details_temp idx_8268085_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vc_webcast_details_temp
    ADD CONSTRAINT idx_8268085_primary PRIMARY KEY (id);


--
-- Name: vc_webcast_history idx_8268090_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vc_webcast_history
    ADD CONSTRAINT idx_8268090_primary PRIMARY KEY (id);


--
-- Name: verify_digital_signature idx_8268102_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.verify_digital_signature
    ADD CONSTRAINT idx_8268102_primary PRIMARY KEY (dsc_serial_no);


--
-- Name: verify_hcor idx_8268108_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.verify_hcor
    ADD CONSTRAINT idx_8268108_primary PRIMARY KEY (diary_no);


--
-- Name: vernacular_orders_judgments idx_8268123_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vernacular_orders_judgments
    ADD CONSTRAINT idx_8268123_primary PRIMARY KEY (id);


--
-- Name: virtual_justice_clock idx_8268133_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.virtual_justice_clock
    ADD CONSTRAINT idx_8268133_primary PRIMARY KEY (id);


--
-- Name: virtual_justice_clock_casetype idx_8268140_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.virtual_justice_clock_casetype
    ADD CONSTRAINT idx_8268140_primary PRIMARY KEY (id);


--
-- Name: virtual_justice_clock_main_subject_category idx_8268161_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.virtual_justice_clock_main_subject_category
    ADD CONSTRAINT idx_8268161_primary PRIMARY KEY (id);


--
-- Name: virtual_justice_clock_scrutiny idx_8268182_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.virtual_justice_clock_scrutiny
    ADD CONSTRAINT idx_8268182_primary PRIMARY KEY (id);


--
-- Name: weekly_list idx_8268203_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.weekly_list
    ADD CONSTRAINT idx_8268203_primary PRIMARY KEY (id);


--
-- Name: whatsapp_pool idx_8268208_primary; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.whatsapp_pool
    ADD CONSTRAINT idx_8268208_primary PRIMARY KEY (id);


--
-- Name: mul_category_caveat mul_category_caveat_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mul_category_caveat
    ADD CONSTRAINT mul_category_caveat_pkey PRIMARY KEY (mul_category_idd);


--
-- Name: ref_keyword ref_keyword_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ref_keyword
    ADD CONSTRAINT ref_keyword_pkey PRIMARY KEY (id);


--
-- Name: idx_8264783_fil_no; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8264783_fil_no ON master.act_section USING btree (act_id);


--
-- Name: idx_8264907_index_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8264907_index_display ON master.authority USING btree (display);


--
-- Name: idx_8264926_adv_code; Type: INDEX; Schema: master; Owner: postgres
--

CREATE UNIQUE INDEX idx_8264926_adv_code ON master.bar USING btree (aor_code);


--
-- Name: idx_8264926_email; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8264926_email ON master.bar USING btree (email);


--
-- Name: idx_8264926_mobile; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8264926_mobile ON master.bar USING btree (mobile);


--
-- Name: idx_8264933_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8264933_display ON master.bench USING btree (display);


--
-- Name: idx_8264978_casecode; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8264978_casecode ON master.casetype USING btree (casecode);


--
-- Name: idx_8264978_index_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8264978_index_display ON master.casetype USING btree (display);


--
-- Name: idx_8265234_content_for; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8265234_content_for ON master.content_for_latestupdates USING btree (content_id);


--
-- Name: idx_8265524_deptcode; Type: INDEX; Schema: master; Owner: postgres
--

CREATE UNIQUE INDEX idx_8265524_deptcode ON master.deptt USING btree (deptcode);


--
-- Name: idx_8265553_sc_code; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8265553_sc_code ON master.disposal USING btree (sc_code);


--
-- Name: idx_8265580_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8265580_display ON master.district USING btree (display);


--
-- Name: idx_8265647_doccode1; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8265647_doccode1 ON master.docmaster USING btree (doccode1);


--
-- Name: idx_8265647_doccode_2; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8265647_doccode_2 ON master.docmaster USING btree (doccode);


--
-- Name: idx_8265647_docplusdoc1; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8265647_docplusdoc1 ON master.docmaster USING btree (doccode, doccode1);


--
-- Name: idx_8265647_sc_doc_code; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8265647_sc_doc_code ON master.docmaster USING btree (sc_doc_code);


--
-- Name: idx_8266175_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266175_display ON master.judge USING btree (display);


--
-- Name: idx_8266175_index_query_proposal; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266175_index_query_proposal ON master.judge USING btree (jcode, is_retired, display);


--
-- Name: idx_8266175_jtype; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266175_jtype ON master.judge USING btree (jtype);


--
-- Name: idx_8266186_unique1; Type: INDEX; Schema: master; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266186_unique1 ON master.judge_category USING btree (j1, priority, to_dt, m_f);


--
-- Name: idx_8266272_law_firm_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266272_law_firm_id ON master.law_firm_adv USING btree (law_firm_id, enroll_no, enroll_yr, state_id, from_date, to_date);


--
-- Name: idx_8266292_lccasename; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266292_lccasename ON master.lc_casetype USING gin (to_tsvector('simple'::regconfig, (lccasename)::text));


--
-- Name: idx_8266299_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266299_id ON master.lc_hc_casetype USING btree (id);


--
-- Name: idx_8266299_index_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266299_index_display ON master.lc_hc_casetype USING btree (display);


--
-- Name: idx_8266326_code; Type: INDEX; Schema: master; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266326_code ON master.listing_purpose USING btree (code);


--
-- Name: idx_8266326_code_2; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266326_code_2 ON master.listing_purpose USING btree (code);


--
-- Name: idx_8266539_mobile; Type: INDEX; Schema: master; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266539_mobile ON master.media_persions USING btree (mobile);


--
-- Name: idx_8266566_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266566_display ON master.menu_old USING btree (display);


--
-- Name: idx_8266566_menu_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266566_menu_id ON master.menu_old USING btree (menu_id);


--
-- Name: idx_8266577_folder_name; Type: INDEX; Schema: master; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266577_folder_name ON master.menu_for_latestupdates USING btree (folder_name);


--
-- Name: idx_8266583_us_code; Type: INDEX; Schema: master; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266583_us_code ON master.mn_me_per USING btree (us_code, mn_me_per);


--
-- Name: idx_8266639_index_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266639_index_display ON master.m_from_court USING btree (display);


--
-- Name: idx_8266819_org_advocate_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266819_org_advocate_id ON master.ntl_judge USING btree (org_advocate_id);


--
-- Name: idx_8266819_org_judge_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266819_org_judge_id ON master.ntl_judge USING btree (org_judge_id);


--
-- Name: idx_8266827_org_advocate_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266827_org_advocate_id ON master.ntl_judge_dept USING btree (dept_id);


--
-- Name: idx_8266827_org_judge_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266827_org_judge_id ON master.ntl_judge_dept USING btree (org_judge_id);


--
-- Name: idx_8266924_reg_agency_state_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266924_reg_agency_state_id ON master.org_lower_court_judges USING btree (reg_agency_state_id);


--
-- Name: idx_8266924_updated_by; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8266924_updated_by ON master.org_lower_court_judges USING btree (updated_by);


--
-- Name: idx_8267027_adm_updated_by; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267027_adm_updated_by ON master.pending_type USING btree (adm_updated_by);


--
-- Name: idx_8267071_index_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267071_index_display ON master.police USING btree (display);


--
-- Name: idx_8267071_index_district; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267071_index_district ON master.police USING btree (cmis_district_id);


--
-- Name: idx_8267071_index_state; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267071_index_state ON master.police USING btree (cmis_state_id);


--
-- Name: idx_8267083_islocal; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267083_islocal ON master.post_distance_master USING btree (is_local);


--
-- Name: idx_8267083_pin; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267083_pin ON master.post_distance_master USING btree (pincode);


--
-- Name: idx_8267099_index_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267099_index_display ON master.post_t USING btree (display);


--
-- Name: idx_8267167_index_cmis_state_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267167_index_cmis_state_id ON master.ref_agency_code USING btree (cmis_state_id);


--
-- Name: idx_8267167_index_is_deleted; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267167_index_is_deleted ON master.ref_agency_code USING btree (is_deleted);


--
-- Name: idx_8267195_adm_updated_by; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267195_adm_updated_by ON master.ref_defect_code USING btree (adm_updated_by);


--
-- Name: idx_8267356_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267356_display ON master.role_menu_mapping USING btree (display);


--
-- Name: idx_8267356_menu_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267356_menu_id ON master.role_menu_mapping USING btree (menu_id);


--
-- Name: idx_8267356_role_master_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267356_role_master_id ON master.role_menu_mapping USING btree (role_master_id);


--
-- Name: idx_8267368_from_date; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267368_from_date ON master.roster USING btree (from_date);


--
-- Name: idx_8267368_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267368_id ON master.roster USING btree (bench_id);


--
-- Name: idx_8267381_judge_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267381_judge_id ON master.roster_judge USING btree (judge_id, display);


--
-- Name: idx_8267381_roster_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267381_roster_id ON master.roster_judge USING btree (roster_id);


--
-- Name: idx_8267405_index_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267405_index_display ON master.rto USING btree (display);


--
-- Name: idx_8267435_index_proposal_query; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267435_index_proposal_query ON master.sc_working_days USING btree (working_date, display, holiday_description);


--
-- Name: idx_8267435_working_date; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267435_working_date ON master.sc_working_days USING btree (working_date);


--
-- Name: idx_8267621_index3; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267621_index3 ON master.stakeholder_details USING btree (cmis_state_id);


--
-- Name: idx_8267621_index4; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267621_index4 ON master.stakeholder_details USING btree (district_id, state_id);


--
-- Name: idx_8267621_index5; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267621_index5 ON master.stakeholder_details USING btree (bench_id);


--
-- Name: idx_8267621_index6; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267621_index6 ON master.stakeholder_details USING btree (jail_id);


--
-- Name: idx_8267621_index7; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267621_index7 ON master.stakeholder_details USING btree (tribunal_id);


--
-- Name: idx_8267621_index8; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267621_index8 ON master.stakeholder_details USING btree (nodal_officer_designation);


--
-- Name: idx_8267621_stakeholder_type_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267621_stakeholder_type_id ON master.stakeholder_details USING btree (stakeholder_type_id);


--
-- Name: idx_8267633_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267633_display ON master.state USING btree (display);


--
-- Name: idx_8267633_index_district; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267633_index_district ON master.state USING btree (district_code);


--
-- Name: idx_8267633_index_sub_district; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267633_index_sub_district ON master.state USING btree (sub_dist_code);


--
-- Name: idx_8267633_index_village; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267633_index_village ON master.state USING btree (village_code);


--
-- Name: idx_8267633_name; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267633_name ON master.state USING btree (state_code);


--
-- Name: idx_8267633_sci_state_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267633_sci_state_id ON master.state USING btree (sci_state_id);


--
-- Name: idx_8267639_listtype; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267639_listtype ON master.subheading USING btree (listtype);


--
-- Name: idx_8267644_subcode1; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267644_subcode1 ON master.submaster USING btree (subcode1);


--
-- Name: idx_8267644_subcode2; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267644_subcode2 ON master.submaster USING btree (subcode2);


--
-- Name: idx_8267644_subcode3; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267644_subcode3 ON master.submaster USING btree (subcode3);


--
-- Name: idx_8267644_subcode4; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267644_subcode4 ON master.submaster USING btree (subcode4);


--
-- Name: idx_8267697_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE UNIQUE INDEX idx_8267697_id ON master.sub_sub_me_per USING btree (id);


--
-- Name: idx_8267880_fk_t_category_t_category_master_idx; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267880_fk_t_category_t_category_master_idx ON master.t_category_master USING btree (parent_id);


--
-- Name: idx_8267880_fk_t_category_t_destination_master_idx; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267880_fk_t_category_t_destination_master_idx ON master.t_category_master USING btree (destination_id);


--
-- Name: idx_8267880_fk_t_category_t_record_status_master_idx; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267880_fk_t_category_t_record_status_master_idx ON master.t_category_master USING btree (record_status);


--
-- Name: idx_8267885_fk_t_doc_details_t_category_master_idx; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267885_fk_t_doc_details_t_category_master_idx ON master.t_doc_details USING btree (category_id);


--
-- Name: idx_8267885_fk_t_doc_details_t_destination_master_idx; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267885_fk_t_doc_details_t_destination_master_idx ON master.t_doc_details USING btree (destination_id);


--
-- Name: idx_8267885_fk_t_doc_details_t_document_store_idx; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267885_fk_t_doc_details_t_document_store_idx ON master.t_doc_details USING btree (document_id);


--
-- Name: idx_8267885_fk_t_doc_details_t_record_status_master_idx; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267885_fk_t_doc_details_t_record_status_master_idx ON master.t_doc_details USING btree (record_status);


--
-- Name: idx_8267885_fk_t_doc_details_t_user_idx; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267885_fk_t_doc_details_t_user_idx ON master.t_doc_details USING btree (by_user_id);


--
-- Name: idx_8267903_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267903_display ON master.users USING btree (display);


--
-- Name: idx_8267903_empid; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267903_empid ON master.users USING btree (empid);


--
-- Name: idx_8267903_ps; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267903_ps ON master.users USING btree (userpass);


--
-- Name: idx_8267903_section; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267903_section ON master.users USING btree (section);


--
-- Name: idx_8267903_usercode; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267903_usercode ON master.users USING btree (usercode);


--
-- Name: idx_8267948_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267948_display ON master.usertype USING btree (display);


--
-- Name: idx_8267960_id; Type: INDEX; Schema: master; Owner: postgres
--

CREATE UNIQUE INDEX idx_8267960_id ON master.user_l_map USING btree (id);


--
-- Name: idx_8267978_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267978_display ON master.user_role_master_mapping USING btree (display);


--
-- Name: idx_8267978_usercode; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267978_usercode ON master.user_role_master_mapping USING btree (usercode);


--
-- Name: idx_8267990_display; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267990_display ON master.user_sec_map USING btree (display);


--
-- Name: idx_8267990_empid; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267990_empid ON master.user_sec_map USING btree (empid);


--
-- Name: idx_8267990_usec; Type: INDEX; Schema: master; Owner: postgres
--

CREATE INDEX idx_8267990_usec ON master.user_sec_map USING btree (usec);


--
-- Name: idx_8264759_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264759_diary_no ON public.abr_accused USING btree (diary_no);


--
-- Name: idx_8264773_act; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264773_act ON public.act_main USING btree (act);


--
-- Name: idx_8264773_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264773_diary_no ON public.act_main USING btree (diary_no);


--
-- Name: idx_8264773_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264773_fil_no ON public.act_main USING btree (id);


--
-- Name: idx_8264816_board_type; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264816_board_type ON public.advanced_drop_note USING btree (board_type);


--
-- Name: idx_8264816_cl_date; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264816_cl_date ON public.advanced_drop_note USING btree (cl_date);


--
-- Name: idx_8264816_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264816_diary_no ON public.advanced_drop_note USING btree (diary_no);


--
-- Name: idx_8264824_board_type; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264824_board_type ON public.advance_allocated USING btree (board_type);


--
-- Name: idx_8264824_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264824_diary_no ON public.advance_allocated USING btree (diary_no);


--
-- Name: idx_8264824_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264824_next_dt ON public.advance_allocated USING btree (next_dt);


--
-- Name: idx_8264847_board_type; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264847_board_type ON public.advance_single_judge_allocated USING btree (board_type);


--
-- Name: idx_8264847_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264847_diary_no ON public.advance_single_judge_allocated USING btree (diary_no);


--
-- Name: idx_8264847_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264847_next_dt ON public.advance_single_judge_allocated USING btree (next_dt);


--
-- Name: idx_8264853_board_type; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264853_board_type ON public.advance_single_judge_allocated_log USING btree (board_type);


--
-- Name: idx_8264853_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264853_diary_no ON public.advance_single_judge_allocated_log USING btree (diary_no);


--
-- Name: idx_8264853_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264853_next_dt ON public.advance_single_judge_allocated_log USING btree (next_dt);


--
-- Name: idx_8264858_advocate_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264858_advocate_id ON public.advocate USING btree (advocate_id);


--
-- Name: idx_8264858_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264858_display ON public.advocate USING btree (display);


--
-- Name: idx_8264858_idx_name; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264858_idx_name ON public.advocate USING btree (diary_no);


--
-- Name: idx_8264913_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264913_diary_no ON public.auto_coram_allottment USING btree (diary_no);


--
-- Name: idx_8264916_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264916_diary_no ON public.avi USING btree (diary_no);


--
-- Name: idx_8264922_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264922_no ON public.a_series USING btree (no);


--
-- Name: idx_8264943_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264943_diary_no ON public.brdrem_his USING btree (diary_no);


--
-- Name: idx_8264987_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8264987_diary_no ON public.case_defect USING btree (diary_no);


--
-- Name: idx_8265022_cl_date; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265022_cl_date ON public.case_remarks_multiple USING btree (cl_date);


--
-- Name: idx_8265022_r_head; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265022_r_head ON public.case_remarks_multiple USING btree (r_head);


--
-- Name: idx_8265054_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265054_diary_no ON public.case_verify USING btree (diary_no);


--
-- Name: idx_8265059_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265059_diary_no ON public.case_verify_by_sec USING btree (diary_no);


--
-- Name: idx_8265069_cl_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265069_cl_dt ON public.case_verify_rop USING btree (cl_dt);


--
-- Name: idx_8265069_diaryno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265069_diaryno ON public.case_verify_rop USING btree (diary_no);


--
-- Name: idx_8265069_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265069_display ON public.case_verify_rop USING btree (display);


--
-- Name: idx_8265069_ent_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265069_ent_dt ON public.case_verify_rop USING btree (ent_dt);


--
-- Name: idx_8265075_ros_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265075_ros_id ON public.category_allottment USING btree (ros_id);


--
-- Name: idx_8265099_casetype_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265099_casetype_id ON public.caveat USING btree (casetype_id);


--
-- Name: idx_8265099_conn_key; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265099_conn_key ON public.caveat USING btree (conn_key);


--
-- Name: idx_8265099_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265099_fil_no ON public.caveat USING btree (fil_no);


--
-- Name: idx_8265112_advocate_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265112_advocate_id ON public.caveat_advocate USING btree (advocate_id);


--
-- Name: idx_8265112_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265112_diary_no ON public.caveat_advocate USING btree (caveat_no);


--
-- Name: idx_8265117_caveat_no_2; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265117_caveat_no_2 ON public.caveat_diary_matching USING btree (caveat_no);


--
-- Name: idx_8265117_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265117_diary_no ON public.caveat_diary_matching USING btree (diary_no);


--
-- Name: idx_8265128_caveat_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265128_caveat_no ON public.caveat_lowerct USING btree (caveat_no);


--
-- Name: idx_8265128_ct_code; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265128_ct_code ON public.caveat_lowerct USING btree (ct_code);


--
-- Name: idx_8265128_l_dist; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265128_l_dist ON public.caveat_lowerct USING btree (l_dist);


--
-- Name: idx_8265128_l_state; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265128_l_state ON public.caveat_lowerct USING btree (l_state);


--
-- Name: idx_8265128_lct_caseno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265128_lct_caseno ON public.caveat_lowerct USING btree (lct_caseno);


--
-- Name: idx_8265128_lct_caseyear; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265128_lct_caseyear ON public.caveat_lowerct USING btree (lct_caseyear);


--
-- Name: idx_8265128_lct_dec_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265128_lct_dec_dt ON public.caveat_lowerct USING btree (lct_dec_dt);


--
-- Name: idx_8265140_caveat_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265140_caveat_no ON public.caveat_party USING btree (caveat_no);


--
-- Name: idx_8265164_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265164_display ON public.cl_freezed USING btree (display);


--
-- Name: idx_8265164_m_f; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265164_m_f ON public.cl_freezed USING btree (m_f);


--
-- Name: idx_8265164_main_supp; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265164_main_supp ON public.cl_freezed USING btree (board_type);


--
-- Name: idx_8265164_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265164_next_dt ON public.cl_freezed USING btree (next_dt);


--
-- Name: idx_8265164_part; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265164_part ON public.cl_freezed USING btree (part);


--
-- Name: idx_8265174_cl_date; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8265174_cl_date ON public.cl_gen USING btree (cl_date);


--
-- Name: idx_8265181_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265181_display ON public.cl_printed USING btree (display);


--
-- Name: idx_8265181_index_query; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265181_index_query ON public.cl_printed USING btree (next_dt, m_f, part, roster_id, display);


--
-- Name: idx_8265181_m_f; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265181_m_f ON public.cl_printed USING btree (m_f);


--
-- Name: idx_8265181_main_supp; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265181_main_supp ON public.cl_printed USING btree (main_supp);


--
-- Name: idx_8265181_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265181_next_dt ON public.cl_printed USING btree (next_dt);


--
-- Name: idx_8265181_part; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265181_part ON public.cl_printed USING btree (part);


--
-- Name: idx_8265181_roster_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265181_roster_id ON public.cl_printed USING btree (roster_id);


--
-- Name: idx_8265193_clp_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265193_clp_id ON public.cl_text_save USING btree (clp_id);


--
-- Name: idx_8265214_conn_key_2; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265214_conn_key_2 ON public.conct USING btree (conn_key);


--
-- Name: idx_8265214_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265214_fil_no ON public.conct USING btree (diary_no);


--
-- Name: idx_8265214_list; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265214_list ON public.conct USING btree (list);


--
-- Name: idx_8265220_conn_key_2; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265220_conn_key_2 ON public.conct_history USING btree (conn_key);


--
-- Name: idx_8265220_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265220_fil_no ON public.conct_history USING btree (diary_no);


--
-- Name: idx_8265253_index_copying_application_documents; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265253_index_copying_application_documents ON public.copying_application_documents USING btree (copying_order_issuing_application_id);


--
-- Name: idx_8265269_application_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265269_application_dt ON public.copying_order_issuing_application_new USING btree (application_receipt);


--
-- Name: idx_8265269_crn; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265269_crn ON public.copying_order_issuing_application_new USING btree (crn);


--
-- Name: idx_8265269_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265269_diary_no ON public.copying_order_issuing_application_new USING btree (diary);


--
-- Name: idx_8265269_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8265269_id ON public.copying_order_issuing_application_new USING btree (id);


--
-- Name: idx_8265269_idx_temp_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265269_idx_temp_id ON public.copying_order_issuing_application_new USING btree (temp_id);


--
-- Name: idx_8265308_copy_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265308_copy_id ON public.copying_request_movement USING btree (copying_request_verify_documents_id);


--
-- Name: idx_8265314_application_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265314_application_dt ON public.copying_request_verify USING btree (application_receipt);


--
-- Name: idx_8265314_crn; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265314_crn ON public.copying_request_verify USING btree (crn);


--
-- Name: idx_8265314_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265314_diary_no ON public.copying_request_verify USING btree (diary);


--
-- Name: idx_8265314_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8265314_id ON public.copying_request_verify USING btree (id);


--
-- Name: idx_8265314_idx_temp_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265314_idx_temp_id ON public.copying_request_verify USING btree (temp_id);


--
-- Name: idx_8265328_index_copying_application_documents; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265328_index_copying_application_documents ON public.copying_request_verify_documents USING btree (copying_order_issuing_application_id);


--
-- Name: idx_8265340_index_copying_application_documents; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265340_index_copying_application_documents ON public.copying_request_verify_documents_log USING btree (copying_order_issuing_application_id);


--
-- Name: idx_8265357_copy_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265357_copy_id ON public.copying_trap USING btree (copying_application_id);


--
-- Name: idx_8265369_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265369_diary_no ON public.coram USING btree (diary_no);


--
-- Name: idx_8265406_conn; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265406_conn ON public.dashboard_data USING btree (with_connected);


--
-- Name: idx_8265406_dacode; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265406_dacode ON public.dashboard_data USING btree (da_code);


--
-- Name: idx_8265406_flag; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265406_flag ON public.dashboard_data USING btree (flag);


--
-- Name: idx_8265406_idd; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265406_idd ON public.dashboard_data USING btree (id);


--
-- Name: idx_8265406_isactive; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265406_isactive ON public.dashboard_data USING btree (is_active);


--
-- Name: idx_8265406_listdate; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265406_listdate ON public.dashboard_data USING btree (list_date);


--
-- Name: idx_8265406_rosterid; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265406_rosterid ON public.dashboard_data USING btree (roster_id);


--
-- Name: idx_8265494_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8265494_diary_no ON public.defects_verification USING btree (diary_no);


--
-- Name: idx_8265535_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265535_diary_no ON public.diary_copy_set USING btree (diary_no);


--
-- Name: idx_8265540_diary_copy_set; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265540_diary_copy_set ON public.diary_movement USING btree (diary_copy_set);


--
-- Name: idx_8265540_disp_by; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265540_disp_by ON public.diary_movement USING btree (disp_by);


--
-- Name: idx_8265540_disp_to; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265540_disp_to ON public.diary_movement USING btree (disp_to);


--
-- Name: idx_8265540_rece_by; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265540_rece_by ON public.diary_movement USING btree (rece_by);


--
-- Name: idx_8265544_disp_by; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265544_disp_by ON public.diary_movement_history USING btree (disp_by);


--
-- Name: idx_8265544_disp_to; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265544_disp_to ON public.diary_movement_history USING btree (disp_to);


--
-- Name: idx_8265544_rece_by; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265544_rece_by ON public.diary_movement_history USING btree (rece_by);


--
-- Name: idx_8265548_faster_details; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265548_faster_details ON public.digital_certification_details USING btree (faster_cases_id, faster_shared_document_details_id, is_deleted);


--
-- Name: idx_8265548_number_year; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265548_number_year ON public.digital_certification_details USING btree (certificate_number, certificate_year, is_deleted);


--
-- Name: idx_8265559_disp_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265559_disp_dt ON public.dispose USING btree (disp_dt);


--
-- Name: idx_8265568_filno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265568_filno ON public.dispose_delete USING btree (fil_no);


--
-- Name: idx_8265568_jud1; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265568_jud1 ON public.dispose_delete USING btree (jud_id, crtstat, bench);


--
-- Name: idx_8265587_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265587_diary_no ON public.docdetails USING btree (diary_no);


--
-- Name: idx_8265587_doccode1; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265587_doccode1 ON public.docdetails USING btree (doccode1);


--
-- Name: idx_8265587_doccode_2; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265587_doccode_2 ON public.docdetails USING btree (doccode);


--
-- Name: idx_8265587_docnum; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265587_docnum ON public.docdetails USING btree (docnum, docyear);


--
-- Name: idx_8265587_ent_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265587_ent_dt ON public.docdetails USING btree (ent_dt);


--
-- Name: idx_8265587_iastat; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265587_iastat ON public.docdetails USING btree (iastat);


--
-- Name: idx_8265587_usercode; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265587_usercode ON public.docdetails USING btree (usercode);


--
-- Name: idx_8265621_party; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265621_party ON public.docdetails_remark USING btree (remark_data);


--
-- Name: idx_8265662_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265662_fil_no ON public.drop_note USING btree (diary_no);


--
-- Name: idx_8265662_jud1; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265662_jud1 ON public.drop_note USING btree (roster_id);


--
-- Name: idx_8265702_action_taken; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265702_action_taken ON public.ec_pil USING btree (ref_action_taken_id);


--
-- Name: idx_8265702_diary; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265702_diary ON public.ec_pil USING btree (diary_number, diary_year);


--
-- Name: idx_8265702_index4; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265702_index4 ON public.ec_pil USING btree (diary_year);


--
-- Name: idx_8265780_ec_postal_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265780_ec_postal_id ON public.ec_postal_transactions USING btree (ec_postal_received_id);


--
-- Name: idx_8265800_idx_efiled_cases_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265800_idx_efiled_cases_diary_no ON public.efiled_cases USING btree (diary_no);


--
-- Name: idx_8265816_diary_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265816_diary_display ON public.efiled_docs USING btree (diary_no, display);


--
-- Name: idx_8265816_doc_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265816_doc_no ON public.efiled_docs USING btree (docnum);


--
-- Name: idx_8265816_doc_year; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265816_doc_year ON public.efiled_docs USING btree (docyear);


--
-- Name: idx_8265835_index_diary; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265835_index_diary ON public.eliminated_cases USING btree (diary_no);


--
-- Name: idx_8265835_index_old_date; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265835_index_old_date ON public.eliminated_cases USING btree (next_dt_old);


--
-- Name: idx_8265840_fil_no_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265840_fil_no_index ON public.elimination USING btree (fil_no);


--
-- Name: idx_8265859_brd_slno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265859_brd_slno ON public.email_hc_cl USING btree (brd_slno);


--
-- Name: idx_8265859_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265859_diary_no ON public.email_hc_cl USING btree (diary_no);


--
-- Name: idx_8265859_index_listing_query; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265859_index_listing_query ON public.email_hc_cl USING btree (diary_no, email, next_dt, mainhead, roster_id, brd_slno, qry_from);


--
-- Name: idx_8265859_mainhead; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265859_mainhead ON public.email_hc_cl USING btree (mainhead);


--
-- Name: idx_8265859_mobile; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265859_mobile ON public.email_hc_cl USING btree (email);


--
-- Name: idx_8265859_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265859_next_dt ON public.email_hc_cl USING btree (next_dt);


--
-- Name: idx_8265859_qry_from; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265859_qry_from ON public.email_hc_cl USING btree (qry_from);


--
-- Name: idx_8265859_roster_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265859_roster_id ON public.email_hc_cl USING btree (roster_id);


--
-- Name: idx_8265859_sent_to_pool; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265859_sent_to_pool ON public.email_hc_cl USING btree (sent_to_smspool);


--
-- Name: idx_8265865_brd_slno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265865_brd_slno ON public.email_hc_cl_17042023 USING btree (brd_slno);


--
-- Name: idx_8265865_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265865_diary_no ON public.email_hc_cl_17042023 USING btree (diary_no);


--
-- Name: idx_8265865_index_listing_query; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265865_index_listing_query ON public.email_hc_cl_17042023 USING btree (diary_no, email, next_dt, mainhead, roster_id, brd_slno, qry_from);


--
-- Name: idx_8265865_mainhead; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265865_mainhead ON public.email_hc_cl_17042023 USING btree (mainhead);


--
-- Name: idx_8265865_mobile; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265865_mobile ON public.email_hc_cl_17042023 USING btree (email);


--
-- Name: idx_8265865_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265865_next_dt ON public.email_hc_cl_17042023 USING btree (next_dt);


--
-- Name: idx_8265865_qry_from; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265865_qry_from ON public.email_hc_cl_17042023 USING btree (qry_from);


--
-- Name: idx_8265865_roster_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265865_roster_id ON public.email_hc_cl_17042023 USING btree (roster_id);


--
-- Name: idx_8265865_sent_to_pool; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265865_sent_to_pool ON public.email_hc_cl_17042023 USING btree (sent_to_smspool);


--
-- Name: idx_8265896_index_faster_case_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265896_index_faster_case_id ON public.faster_communication_details USING btree (faster_cases_id, is_deleted);


--
-- Name: idx_8265965_d_to_empid; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265965_d_to_empid ON public.fil_trap USING btree (d_to_empid);


--
-- Name: idx_8265965_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8265965_diary_no ON public.fil_trap USING btree (diary_no);


--
-- Name: idx_8265965_disp_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265965_disp_dt ON public.fil_trap USING btree (disp_dt);


--
-- Name: idx_8265965_remarks; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265965_remarks ON public.fil_trap USING btree (remarks);


--
-- Name: idx_8265974_d_to_empid; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265974_d_to_empid ON public.fil_trap_his USING btree (d_to_empid);


--
-- Name: idx_8265974_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265974_diary_no ON public.fil_trap_his USING btree (diary_no);


--
-- Name: idx_8265974_r_by_empid; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265974_r_by_empid ON public.fil_trap_his USING btree (r_by_empid);


--
-- Name: idx_8265974_remarks; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265974_remarks ON public.fil_trap_his USING btree (remarks);


--
-- Name: idx_8265988_ddate; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8265988_ddate ON public.fil_trap_seq USING btree (ddate);


--
-- Name: idx_8266005_case_type; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266005_case_type ON public.free_text_rop USING btree (case_type);


--
-- Name: idx_8266005_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266005_diary_no ON public.free_text_rop USING btree (diary_no);


--
-- Name: idx_8266023_brddt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266023_brddt ON public.headfooter USING btree (next_dt);


--
-- Name: idx_8266023_brdstat; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266023_brdstat ON public.headfooter USING btree (next_dt, roster_id);


--
-- Name: idx_8266029_board_type; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266029_board_type ON public.heardt USING btree (board_type);


--
-- Name: idx_8266029_clno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266029_clno ON public.heardt USING btree (clno);


--
-- Name: idx_8266029_conn_key; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266029_conn_key ON public.heardt USING btree (conn_key);


--
-- Name: idx_8266029_heardt_next_dt_brd_slno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266029_heardt_next_dt_brd_slno ON public.heardt USING btree (brd_slno);


--
-- Name: idx_8266029_heardt_roster_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266029_heardt_roster_id ON public.heardt USING btree (roster_id);


--
-- Name: idx_8266029_is_nmd; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266029_is_nmd ON public.heardt USING btree (is_nmd);


--
-- Name: idx_8266029_listorder; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266029_listorder ON public.heardt USING btree (listorder);


--
-- Name: idx_8266029_mainhead; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266029_mainhead ON public.heardt USING btree (mainhead);


--
-- Name: idx_8266029_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266029_next_dt ON public.heardt USING btree (next_dt);


--
-- Name: idx_8266029_subhead; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266029_subhead ON public.heardt USING btree (subhead);


--
-- Name: idx_8266047_board_type; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266047_board_type ON public.heardt_webuse USING btree (board_type);


--
-- Name: idx_8266047_clno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266047_clno ON public.heardt_webuse USING btree (clno);


--
-- Name: idx_8266047_conn_key; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266047_conn_key ON public.heardt_webuse USING btree (conn_key);


--
-- Name: idx_8266047_heardt_next_dt_brd_slno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266047_heardt_next_dt_brd_slno ON public.heardt_webuse USING btree (brd_slno);


--
-- Name: idx_8266047_heardt_roster_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266047_heardt_roster_id ON public.heardt_webuse USING btree (roster_id);


--
-- Name: idx_8266047_is_nmd; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266047_is_nmd ON public.heardt_webuse USING btree (is_nmd);


--
-- Name: idx_8266047_listorder; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266047_listorder ON public.heardt_webuse USING btree (listorder);


--
-- Name: idx_8266047_mainhead; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266047_mainhead ON public.heardt_webuse USING btree (mainhead);


--
-- Name: idx_8266047_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266047_next_dt ON public.heardt_webuse USING btree (next_dt);


--
-- Name: idx_8266047_subhead; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266047_subhead ON public.heardt_webuse USING btree (subhead);


--
-- Name: idx_8266135_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266135_diary_no ON public.indexing USING btree (diary_no);


--
-- Name: idx_8266135_upd_tif_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266135_upd_tif_id ON public.indexing USING btree (upd_tif_id);


--
-- Name: idx_8266167_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266167_diary_no ON public.jail_petition_details USING btree (diary_no, diary_no_entry_dt);


--
-- Name: idx_8266172_cl_date; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266172_cl_date ON public.jo_alottment_paps USING btree (cl_date);


--
-- Name: idx_8266231_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266231_fil_no ON public.kept_below USING btree (fil_no);


--
-- Name: idx_8266231_kb_key; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266231_kb_key ON public.kept_below USING btree (kb_key);


--
-- Name: idx_8266243_conn_key; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266243_conn_key ON public.last_heardt USING btree (conn_key);


--
-- Name: idx_8266243_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266243_diary_no ON public.last_heardt USING btree (diary_no);


--
-- Name: idx_8266243_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266243_next_dt ON public.last_heardt USING btree (next_dt);


--
-- Name: idx_8266254_conn_key; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266254_conn_key ON public.last_heardt_webuse USING btree (conn_key);


--
-- Name: idx_8266254_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266254_diary_no ON public.last_heardt_webuse USING btree (diary_no);


--
-- Name: idx_8266254_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266254_next_dt ON public.last_heardt_webuse USING btree (next_dt);


--
-- Name: idx_8266277_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266277_diary_no ON public.law_points USING btree (diary_no);


--
-- Name: idx_8266305_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266305_diary_no ON public.ld_move USING btree (diary_no);


--
-- Name: idx_8266305_disp_by; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266305_disp_by ON public.ld_move USING btree (disp_by);


--
-- Name: idx_8266305_disp_to; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266305_disp_to ON public.ld_move USING btree (disp_to);


--
-- Name: idx_8266305_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266305_fil_no ON public.ld_move USING btree (fil_no);


--
-- Name: idx_8266305_rece_by; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266305_rece_by ON public.ld_move USING btree (rece_by);


--
-- Name: idx_8266311_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266311_diary_no ON public.ld_move_30102018 USING btree (diary_no);


--
-- Name: idx_8266311_disp_by; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266311_disp_by ON public.ld_move_30102018 USING btree (disp_by);


--
-- Name: idx_8266311_disp_to; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266311_disp_to ON public.ld_move_30102018 USING btree (disp_to);


--
-- Name: idx_8266311_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266311_fil_no ON public.ld_move_30102018 USING btree (fil_no);


--
-- Name: idx_8266311_rece_by; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266311_rece_by ON public.ld_move_30102018 USING btree (rece_by);


--
-- Name: idx_8266331_usercode; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266331_usercode ON public.log_check USING btree (usercode);


--
-- Name: idx_8266335_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266335_id ON public.loose_block USING btree (id);


--
-- Name: idx_8266341_ct_code; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266341_ct_code ON public.lowerct USING btree (ct_code);


--
-- Name: idx_8266341_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266341_fil_no ON public.lowerct USING btree (diary_no);


--
-- Name: idx_8266341_index_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266341_index_display ON public.lowerct USING btree (lw_display);


--
-- Name: idx_8266341_index_is_order_challenged; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266341_index_is_order_challenged ON public.lowerct USING btree (is_order_challenged);


--
-- Name: idx_8266341_l_dist; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266341_l_dist ON public.lowerct USING btree (l_dist);


--
-- Name: idx_8266341_l_state; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266341_l_state ON public.lowerct USING btree (l_state);


--
-- Name: idx_8266341_lct_caseno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266341_lct_caseno ON public.lowerct USING btree (lct_caseno);


--
-- Name: idx_8266341_lct_casetype; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266341_lct_casetype ON public.lowerct USING btree (lct_casetype);


--
-- Name: idx_8266341_lct_caseyear; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266341_lct_caseyear ON public.lowerct USING btree (lct_caseyear);


--
-- Name: idx_8266341_lct_dec_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266341_lct_dec_dt ON public.lowerct USING btree (lct_dec_dt);


--
-- Name: idx_8266341_vehicle_code; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266341_vehicle_code ON public.lowerct USING btree (vehicle_code);


--
-- Name: idx_8266341_vehicle_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266341_vehicle_no ON public.lowerct USING btree (vehicle_no);


--
-- Name: idx_8266354_indx_lowect_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266354_indx_lowect_id ON public.lowerct_judges USING btree (lowerct_id);


--
-- Name: idx_8266359_active_casetype_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266359_active_casetype_id ON public.main USING btree (active_casetype_id);


--
-- Name: idx_8266359_active_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266359_active_fil_no ON public.main USING btree (active_fil_no);


--
-- Name: idx_8266359_active_reg_year; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266359_active_reg_year ON public.main USING btree (active_reg_year);


--
-- Name: idx_8266359_casetype_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266359_casetype_id ON public.main USING btree (casetype_id);


--
-- Name: idx_8266359_conn_key; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266359_conn_key ON public.main USING btree (conn_key);


--
-- Name: idx_8266359_dacode; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266359_dacode ON public.main USING btree (dacode);


--
-- Name: idx_8266359_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266359_fil_no ON public.main USING btree (fil_no);


--
-- Name: idx_8266359_fil_no_fh; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266359_fil_no_fh ON public.main USING btree (fil_no_fh);


--
-- Name: idx_8266359_index_c_status; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266359_index_c_status ON public.main USING btree (c_status);


--
-- Name: idx_8266359_ref_agency_code_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266359_ref_agency_code_id ON public.main USING btree (ref_agency_code_id);


--
-- Name: idx_8266359_ref_agency_state_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266359_ref_agency_state_id ON public.main USING btree (ref_agency_state_id);


--
-- Name: idx_8266359_section_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266359_section_id ON public.main USING btree (section_id);


--
-- Name: idx_8266359_usercode; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266359_usercode ON public.main USING btree (usercode);


--
-- Name: idx_8266371_active_casetype_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266371_active_casetype_id ON public.main_backup_data_correction USING btree (active_casetype_id);


--
-- Name: idx_8266371_active_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266371_active_fil_no ON public.main_backup_data_correction USING btree (active_fil_no);


--
-- Name: idx_8266371_active_reg_year; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266371_active_reg_year ON public.main_backup_data_correction USING btree (active_reg_year);


--
-- Name: idx_8266371_casetype_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266371_casetype_id ON public.main_backup_data_correction USING btree (casetype_id);


--
-- Name: idx_8266371_conn_key; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266371_conn_key ON public.main_backup_data_correction USING btree (conn_key);


--
-- Name: idx_8266371_dacode; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266371_dacode ON public.main_backup_data_correction USING btree (dacode);


--
-- Name: idx_8266371_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266371_fil_no ON public.main_backup_data_correction USING btree (fil_no);


--
-- Name: idx_8266371_fil_no_fh; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266371_fil_no_fh ON public.main_backup_data_correction USING btree (fil_no_fh);


--
-- Name: idx_8266371_ref_agency_code_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266371_ref_agency_code_id ON public.main_backup_data_correction USING btree (ref_agency_code_id);


--
-- Name: idx_8266371_ref_agency_state_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266371_ref_agency_state_id ON public.main_backup_data_correction USING btree (ref_agency_state_id);


--
-- Name: idx_8266371_section_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266371_section_id ON public.main_backup_data_correction USING btree (section_id);


--
-- Name: idx_8266371_usercode; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266371_usercode ON public.main_backup_data_correction USING btree (usercode);


--
-- Name: idx_8266383_active_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266383_active_fil_no ON public.main_cancel_reg USING btree (active_fil_no);


--
-- Name: idx_8266383_casetype_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266383_casetype_id ON public.main_cancel_reg USING btree (casetype_id);


--
-- Name: idx_8266383_conn_key; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266383_conn_key ON public.main_cancel_reg USING btree (conn_key);


--
-- Name: idx_8266383_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266383_fil_no ON public.main_cancel_reg USING btree (fil_no);


--
-- Name: idx_8266383_fil_no_fh; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266383_fil_no_fh ON public.main_cancel_reg USING btree (fil_no_fh);


--
-- Name: idx_8266383_ref_agency_code_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266383_ref_agency_code_id ON public.main_cancel_reg USING btree (ref_agency_code_id);


--
-- Name: idx_8266383_ref_agency_state_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266383_ref_agency_state_id ON public.main_cancel_reg USING btree (ref_agency_state_id);


--
-- Name: idx_8266396_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266396_diary_no ON public.main_casetype_history USING btree (diary_no);


--
-- Name: idx_8266396_is_deleted; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266396_is_deleted ON public.main_casetype_history USING btree (is_deleted);


--
-- Name: idx_8266396_new_reg_year; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266396_new_reg_year ON public.main_casetype_history USING btree (new_registration_year);


--
-- Name: idx_8266396_new_registration_number; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266396_new_registration_number ON public.main_casetype_history USING btree (new_registration_number);


--
-- Name: idx_8266396_old_reg_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266396_old_reg_no ON public.main_casetype_history USING btree (old_registration_number);


--
-- Name: idx_8266396_old_reg_year; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266396_old_reg_year ON public.main_casetype_history USING btree (old_registration_year);


--
-- Name: idx_8266402_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266402_diary_no ON public.main_casetype_history_backup_data_correction USING btree (diary_no);


--
-- Name: idx_8266402_is_deleted; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266402_is_deleted ON public.main_casetype_history_backup_data_correction USING btree (is_deleted);


--
-- Name: idx_8266402_new_reg_year; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266402_new_reg_year ON public.main_casetype_history_backup_data_correction USING btree (new_registration_year);


--
-- Name: idx_8266402_new_registration_number; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266402_new_registration_number ON public.main_casetype_history_backup_data_correction USING btree (new_registration_number);


--
-- Name: idx_8266402_old_reg_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266402_old_reg_no ON public.main_casetype_history_backup_data_correction USING btree (old_registration_number);


--
-- Name: idx_8266402_old_reg_year; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266402_old_reg_year ON public.main_casetype_history_backup_data_correction USING btree (old_registration_year);


--
-- Name: idx_8266418_active_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266418_active_fil_no ON public.main_deleted_cases USING btree (active_fil_no);


--
-- Name: idx_8266418_casetype_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266418_casetype_id ON public.main_deleted_cases USING btree (casetype_id);


--
-- Name: idx_8266418_conn_key; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266418_conn_key ON public.main_deleted_cases USING btree (conn_key);


--
-- Name: idx_8266418_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266418_fil_no ON public.main_deleted_cases USING btree (fil_no);


--
-- Name: idx_8266418_fil_no_fh; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266418_fil_no_fh ON public.main_deleted_cases USING btree (fil_no_fh);


--
-- Name: idx_8266418_ref_agency_code_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266418_ref_agency_code_id ON public.main_deleted_cases USING btree (ref_agency_code_id);


--
-- Name: idx_8266418_ref_agency_state_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266418_ref_agency_state_id ON public.main_deleted_cases USING btree (ref_agency_state_id);


--
-- Name: idx_8266430_active_casetype_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266430_active_casetype_id ON public.main_ingestion USING btree (active_casetype_id);


--
-- Name: idx_8266430_active_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266430_active_fil_no ON public.main_ingestion USING btree (active_fil_no);


--
-- Name: idx_8266430_active_reg_year; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266430_active_reg_year ON public.main_ingestion USING btree (active_reg_year);


--
-- Name: idx_8266430_casetype_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266430_casetype_id ON public.main_ingestion USING btree (casetype_id);


--
-- Name: idx_8266430_conn_key; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266430_conn_key ON public.main_ingestion USING btree (conn_key);


--
-- Name: idx_8266430_dacode; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266430_dacode ON public.main_ingestion USING btree (dacode);


--
-- Name: idx_8266430_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266430_fil_no ON public.main_ingestion USING btree (fil_no);


--
-- Name: idx_8266430_fil_no_fh; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266430_fil_no_fh ON public.main_ingestion USING btree (fil_no_fh);


--
-- Name: idx_8266430_ref_agency_code_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266430_ref_agency_code_id ON public.main_ingestion USING btree (ref_agency_code_id);


--
-- Name: idx_8266430_ref_agency_state_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266430_ref_agency_state_id ON public.main_ingestion USING btree (ref_agency_state_id);


--
-- Name: idx_8266430_section_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266430_section_id ON public.main_ingestion USING btree (section_id);


--
-- Name: idx_8266430_usercode; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266430_usercode ON public.main_ingestion USING btree (usercode);


--
-- Name: idx_8266550_mentionmemo_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266550_mentionmemo_index ON public.mention_memo USING btree (diary_no, date_of_received, date_on_decided, date_for_decided, m_roster_id);


--
-- Name: idx_8266589_mobile_number; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266589_mobile_number ON public.mobile_numbers_wa USING btree (mobile_number);


--
-- Name: idx_8266622_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266622_diary_no ON public.mul_category USING btree (diary_no);


--
-- Name: idx_8266622_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266622_display ON public.mul_category USING btree (display);


--
-- Name: idx_8266622_submaster_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266622_submaster_id ON public.mul_category USING btree (submaster_id);


--
-- Name: idx_8266677_unique_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266677_unique_index ON public.neutral_citation USING btree (diary_no, order_type, dispose_order_date);


--
-- Name: idx_8266685_unique_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266685_unique_index ON public.neutral_citation_01072023 USING btree (diary_no, order_type, dispose_order_date);


--
-- Name: idx_8266693_unique_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266693_unique_index ON public.neutral_citation_06072023 USING btree (diary_no, order_type, dispose_order_date);


--
-- Name: idx_8266701_unique_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266701_unique_index ON public.neutral_citation_24042023 USING btree (diary_no, order_type, dispose_order_date);


--
-- Name: idx_8266717_index1; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266717_index1 ON public.new_subject_category_updation USING btree (diary_no);


--
-- Name: idx_8266717_index_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266717_index_display ON public.new_subject_category_updation USING btree (display);


--
-- Name: idx_8266717_index_update_user_type; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266717_index_update_user_type ON public.new_subject_category_updation USING btree (updated_by_user_type);


--
-- Name: idx_8266739_cino; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266739_cino ON public.njdg_category_transaction USING btree (cino);


--
-- Name: idx_8266739_create_modify; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266739_create_modify ON public.njdg_category_transaction USING btree (create_modify);


--
-- Name: idx_8266739_entry_source_flag; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266739_entry_source_flag ON public.njdg_category_transaction USING btree (entry_source_flag);


--
-- Name: idx_8266739_insert_date_time; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266739_insert_date_time ON public.njdg_category_transaction USING btree (insert_date_time);


--
-- Name: idx_8266744_cnr_running; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266744_cnr_running ON public.njdg_cino USING btree (cnr_running);


--
-- Name: idx_8266744_cnr_year; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266744_cnr_year ON public.njdg_cino USING btree (cnr_year);


--
-- Name: idx_8266748_cino; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266748_cino ON public.njdg_lower_court USING btree (cino);


--
-- Name: idx_8266753_cino; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266753_cino ON public.njdg_ordernet USING btree (cino);


--
-- Name: idx_8266753_dno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266753_dno ON public.njdg_ordernet USING btree (diary_no);


--
-- Name: idx_8266773_create_modify; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266773_create_modify ON public.njdg_transaction USING btree (create_modify);


--
-- Name: idx_8266773_dno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266773_dno ON public.njdg_transaction USING btree (diary_no);


--
-- Name: idx_8266773_entry_source_flag; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266773_entry_source_flag ON public.njdg_transaction USING btree (entry_source_flag);


--
-- Name: idx_8266773_from_cino_conver; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266773_from_cino_conver ON public.njdg_transaction USING btree (from_cino_conversion);


--
-- Name: idx_8266773_history_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266773_history_id ON public.njdg_transaction USING btree (main_casetype_history_id);


--
-- Name: idx_8266773_icmis_reg_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266773_icmis_reg_no ON public.njdg_transaction USING btree (icmis_registration_no);


--
-- Name: idx_8266773_insert_date_time; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266773_insert_date_time ON public.njdg_transaction USING btree (insert_date_time);


--
-- Name: idx_8266773_to_cino_conver; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266773_to_cino_conver ON public.njdg_transaction USING btree (to_cino_conversion);


--
-- Name: idx_8266799_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266799_diary_no ON public.not_before USING btree (diary_no, j1);


--
-- Name: idx_8266807_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266807_diary_no ON public.not_before_his USING btree (diary_no);


--
-- Name: idx_8266838_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266838_diary_no ON public.objrem USING btree (diary_no);


--
-- Name: idx_8266844_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266844_diary_no ON public.obj_save USING btree (diary_no);


--
-- Name: idx_8266844_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266844_display ON public.obj_save USING btree (display);


--
-- Name: idx_8266844_rm_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266844_rm_dt ON public.obj_save USING btree (rm_dt);


--
-- Name: idx_8266864_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266864_diary_no ON public.office_report_details USING btree (diary_no);


--
-- Name: idx_8266864_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266864_display ON public.office_report_details USING btree (display);


--
-- Name: idx_8266864_order_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266864_order_dt ON public.office_report_details USING btree (order_dt);


--
-- Name: idx_8266864_web_status; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266864_web_status ON public.office_report_details USING btree (web_status);


--
-- Name: idx_8266878_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266878_fil_no ON public.ordernet USING btree (diary_no, orderdate);


--
-- Name: idx_8266890_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266890_fil_no ON public.ordernet_deleted USING btree (diary_no, orderdate);


--
-- Name: idx_8266902_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266902_fil_no ON public.ordernet_org USING btree (diary_no, orderdate);


--
-- Name: idx_8266934_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266934_display ON public.or_gist USING btree (display);


--
-- Name: idx_8266934_dno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266934_dno ON public.or_gist USING btree (diary_no);


--
-- Name: idx_8266934_list_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266934_list_dt ON public.or_gist USING btree (list_dt);


--
-- Name: idx_8266956_fk_otp_sent_detail_1_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266956_fk_otp_sent_detail_1_idx ON public.otp_sent_detail USING btree (otp_based_login_history_id);


--
-- Name: idx_8266973_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8266973_fil_no ON public.pap_book USING btree (fil_no, display);


--
-- Name: idx_8266980_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266980_diary_no ON public.party USING btree (diary_no, pet_res, sr_no);


--
-- Name: idx_8266980_diary_no_2; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266980_diary_no_2 ON public.party USING btree (diary_no);


--
-- Name: idx_8266980_email; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8266980_email ON public.party USING btree (email);


--
-- Name: idx_8267000_addr; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267000_addr ON public.party_autocomp USING btree (addr);


--
-- Name: idx_8267000_party; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267000_party ON public.party_autocomp USING btree (party);


--
-- Name: idx_8267006_party_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267006_party_id ON public.party_lowercourt USING btree (party_id);


--
-- Name: idx_8267012_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8267012_id ON public.party_order USING btree (id);


--
-- Name: idx_8267018_cat; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267018_cat ON public.pendency_report USING btree (submaster_id);


--
-- Name: idx_8267077_application; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267077_application ON public.post_bar_code_mapping USING btree (copying_application_id);


--
-- Name: idx_8267077_barcode; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267077_barcode ON public.post_bar_code_mapping USING btree (barcode);


--
-- Name: idx_8267135_disp_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267135_disp_dt ON public.recalled_deleted USING btree (disp_dt);


--
-- Name: idx_8267135_ord_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267135_ord_dt ON public.recalled_deleted USING btree (ord_dt);


--
-- Name: idx_8267138_disp_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267138_disp_dt ON public.recalled_matters USING btree (disp_dt);


--
-- Name: idx_8267138_ord_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267138_ord_dt ON public.recalled_matters USING btree (ord_dt);


--
-- Name: idx_8267141_disp_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267141_disp_dt ON public.recalled_matters_21122018 USING btree (disp_dt);


--
-- Name: idx_8267141_ord_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267141_ord_dt ON public.recalled_matters_21122018 USING btree (ord_dt);


--
-- Name: idx_8267145_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267145_diary_no ON public.record_keeping USING btree (diary_no);


--
-- Name: idx_8267294_casetype_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267294_casetype_id ON public.registered_cases USING btree (casetype_id, case_no, case_year);


--
-- Name: idx_8267308_index_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267308_index_display ON public.relied_details USING btree (display);


--
-- Name: idx_8267308_index_lowerctid; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267308_index_lowerctid ON public.relied_details USING btree (lowerct_id);


--
-- Name: idx_8267308_relied_court; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267308_relied_court ON public.relied_details USING btree (relied_court, relied_case_type, relied_case_no, relied_case_year, relied_state, relied_district);


--
-- Name: idx_8267324_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267324_fil_no ON public.restored USING btree (diary_no);


--
-- Name: idx_8267337_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267337_fil_no ON public.rgo_default USING btree (fil_no);


--
-- Name: idx_8267343_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267343_fil_no ON public.rgo_default_history USING btree (fil_no);


--
-- Name: idx_8267427_caseyr; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267427_caseyr ON public.scordermain USING btree (caseyr, number, cis_typecode);


--
-- Name: idx_8267427_caseyr_2; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267427_caseyr_2 ON public.scordermain USING btree (caseyr);


--
-- Name: idx_8267427_cis_typecode; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267427_cis_typecode ON public.scordermain USING btree (cis_typecode);


--
-- Name: idx_8267427_number; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267427_number ON public.scordermain USING btree (number);


--
-- Name: idx_8267458_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267458_diary_no ON public.sensitive_cases USING btree (diary_no);


--
-- Name: idx_8267527_board_type; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267527_board_type ON public.single_judge_advanced_drop_note USING btree (board_type);


--
-- Name: idx_8267527_cl_date; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267527_cl_date ON public.single_judge_advanced_drop_note USING btree (cl_date);


--
-- Name: idx_8267527_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267527_diary_no ON public.single_judge_advanced_drop_note USING btree (diary_no);


--
-- Name: idx_8267578_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267578_diary_no ON public.sms_drop_cl USING btree (diary_no);


--
-- Name: idx_8267578_mobile; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267578_mobile ON public.sms_drop_cl USING btree (mobile);


--
-- Name: idx_8267582_brd_slno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267582_brd_slno ON public.sms_hc_cl USING btree (brd_slno);


--
-- Name: idx_8267582_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267582_diary_no ON public.sms_hc_cl USING btree (diary_no);


--
-- Name: idx_8267582_index_listing_query; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267582_index_listing_query ON public.sms_hc_cl USING btree (diary_no, mobile, next_dt, mainhead, roster_id, brd_slno, qry_from);


--
-- Name: idx_8267582_mainhead; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267582_mainhead ON public.sms_hc_cl USING btree (mainhead);


--
-- Name: idx_8267582_mobile; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267582_mobile ON public.sms_hc_cl USING btree (mobile);


--
-- Name: idx_8267582_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267582_next_dt ON public.sms_hc_cl USING btree (next_dt);


--
-- Name: idx_8267582_qry_from; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267582_qry_from ON public.sms_hc_cl USING btree (qry_from);


--
-- Name: idx_8267582_roster_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267582_roster_id ON public.sms_hc_cl USING btree (roster_id);


--
-- Name: idx_8267582_sent_to_pool; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267582_sent_to_pool ON public.sms_hc_cl USING btree (sent_to_smspool);


--
-- Name: idx_8267586_brd_slno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267586_brd_slno ON public.sms_hc_cl_17042023 USING btree (brd_slno);


--
-- Name: idx_8267586_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267586_diary_no ON public.sms_hc_cl_17042023 USING btree (diary_no);


--
-- Name: idx_8267586_index_listing_query; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267586_index_listing_query ON public.sms_hc_cl_17042023 USING btree (diary_no, mobile, next_dt, mainhead, roster_id, brd_slno, qry_from);


--
-- Name: idx_8267586_mainhead; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267586_mainhead ON public.sms_hc_cl_17042023 USING btree (mainhead);


--
-- Name: idx_8267586_mobile; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267586_mobile ON public.sms_hc_cl_17042023 USING btree (mobile);


--
-- Name: idx_8267586_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267586_next_dt ON public.sms_hc_cl_17042023 USING btree (next_dt);


--
-- Name: idx_8267586_qry_from; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267586_qry_from ON public.sms_hc_cl_17042023 USING btree (qry_from);


--
-- Name: idx_8267586_roster_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267586_roster_id ON public.sms_hc_cl_17042023 USING btree (roster_id);


--
-- Name: idx_8267586_sent_to_pool; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267586_sent_to_pool ON public.sms_hc_cl_17042023 USING btree (sent_to_smspool);


--
-- Name: idx_8267591_mobile; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267591_mobile ON public.sms_pool USING btree (mobile);


--
-- Name: idx_8267609_diary_no_idx; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267609_diary_no_idx ON public.special_category_filing USING btree (diary_no);


--
-- Name: idx_8267655_subcode1; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267655_subcode1 ON public.submaster_old USING btree (subcode1);


--
-- Name: idx_8267655_subcode2; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267655_subcode2 ON public.submaster_old USING btree (subcode2);


--
-- Name: idx_8267655_subcode3; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267655_subcode3 ON public.submaster_old USING btree (subcode3);


--
-- Name: idx_8267655_subcode4; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267655_subcode4 ON public.submaster_old USING btree (subcode4);


--
-- Name: idx_8267703_idx_current_status; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267703_idx_current_status ON public.tbl_court_requisition USING btree (current_status);


--
-- Name: idx_8267703_idx_status; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267703_idx_status ON public.tbl_court_requisition USING btree (status);


--
-- Name: idx_8267748_jm; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267748_jm ON public.tempo USING btree (diary_no);


--
-- Name: idx_8267753_jm; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267753_jm ON public.tempo_deleted USING btree (diary_no);


--
-- Name: idx_8267780_index_diary; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267780_index_diary ON public.transfer_old_com_gen_cases USING btree (diary_no);


--
-- Name: idx_8267780_index_old_date; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267780_index_old_date ON public.transfer_old_com_gen_cases USING btree (next_dt_old);


--
-- Name: idx_8267785_index_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267785_index_display ON public.transfer_to_details USING btree (display);


--
-- Name: idx_8267785_index_lowerctid; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267785_index_lowerctid ON public.transfer_to_details USING btree (lowerct_id);


--
-- Name: idx_8267795_dispatch_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267795_dispatch_dt ON public.tw_comp_not USING btree (dispatch_dt);


--
-- Name: idx_8267795_tw_o_r_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267795_tw_o_r_id ON public.tw_comp_not USING btree (copy_type);


--
-- Name: idx_8267795_tw_sn_to; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267795_tw_sn_to ON public.tw_comp_not USING btree (tw_sn_to);


--
-- Name: idx_8267795_tw_tal_del_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267795_tw_tal_del_id ON public.tw_comp_not USING btree (tw_o_r_id);


--
-- Name: idx_8267803_dispatch_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267803_dispatch_dt ON public.tw_comp_not_history USING btree (dispatch_dt);


--
-- Name: idx_8267803_tw_o_r_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267803_tw_o_r_id ON public.tw_comp_not_history USING btree (copy_type);


--
-- Name: idx_8267803_tw_sn_to; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267803_tw_sn_to ON public.tw_comp_not_history USING btree (tw_sn_to);


--
-- Name: idx_8267803_tw_tal_del_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267803_tw_tal_del_id ON public.tw_comp_not_history USING btree (tw_o_r_id);


--
-- Name: idx_8267828_tw_org_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267828_tw_org_id ON public.tw_o_r USING btree (tw_org_id);


--
-- Name: idx_8267844_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267844_fil_no ON public.tw_pro_desc USING btree (diary_no, sr_no);


--
-- Name: idx_8267866_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267866_fil_no ON public.tw_tal_del USING btree (diary_no);


--
-- Name: idx_8267866_process_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_8267866_process_id ON public.tw_tal_del USING btree (process_id, rec_dt);


--
-- Name: idx_8267924_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267924_display ON public.users_22092000 USING btree (display);


--
-- Name: idx_8267924_empid; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267924_empid ON public.users_22092000 USING btree (empid);


--
-- Name: idx_8267924_ps; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267924_ps ON public.users_22092000 USING btree (userpass);


--
-- Name: idx_8267936_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267936_display ON public.users_dump USING btree (display);


--
-- Name: idx_8267936_empid; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267936_empid ON public.users_dump USING btree (empid);


--
-- Name: idx_8267936_ps; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267936_ps ON public.users_dump USING btree (userpass);


--
-- Name: idx_8267996_dno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267996_dno ON public.vacation_advance_list USING btree (diary_no);


--
-- Name: idx_8267996_isdel; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267996_isdel ON public.vacation_advance_list USING btree (is_deleted);


--
-- Name: idx_8267996_vyr; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267996_vyr ON public.vacation_advance_list USING btree (vacation_list_year);


--
-- Name: idx_8267999_dno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267999_dno ON public.vacation_advance_list_advocate USING btree (diary_no);


--
-- Name: idx_8267999_isdle; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267999_isdle ON public.vacation_advance_list_advocate USING btree (is_deleted);


--
-- Name: idx_8267999_vyr; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8267999_vyr ON public.vacation_advance_list_advocate USING btree (vacation_list_year);


--
-- Name: idx_8268025_aor_code; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268025_aor_code ON public.vacation_advance_list_advocate_old USING btree (aor_code);


--
-- Name: idx_8268025_dno; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268025_dno ON public.vacation_advance_list_advocate_old USING btree (diary_no);


--
-- Name: idx_8268025_is_del; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268025_is_del ON public.vacation_advance_list_advocate_old USING btree (is_deleted);


--
-- Name: idx_8268025_isfix; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268025_isfix ON public.vacation_advance_list_advocate_old USING btree (is_fixed);


--
-- Name: idx_8268025_vyear; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268025_vyear ON public.vacation_advance_list_advocate_old USING btree (vacation_list_year);


--
-- Name: idx_8268046_sl_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268046_sl_no ON public.vacation_list_data USING btree (sl_no);


--
-- Name: idx_8268049_diary_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268049_diary_no ON public.vacation_registrar_not_ready_cl USING btree (diary_no);


--
-- Name: idx_8268058_display; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268058_display ON public.vc_room_details USING btree (display);


--
-- Name: idx_8268058_next_dt; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268058_next_dt ON public.vc_room_details USING btree (next_dt);


--
-- Name: idx_8268058_roster_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268058_roster_id ON public.vc_room_details USING btree (roster_id);


--
-- Name: idx_8268123_fil_no; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268123_fil_no ON public.vernacular_orders_judgments USING btree (diary_no, order_date, ref_vernacular_languages_id);


--
-- Name: idx_8268133_flag; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268133_flag ON public.virtual_justice_clock USING btree (flag);


--
-- Name: idx_8268133_idd; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268133_idd ON public.virtual_justice_clock USING btree (id);


--
-- Name: idx_8268133_isactive; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268133_isactive ON public.virtual_justice_clock USING btree (is_active);


--
-- Name: idx_8268133_listdate; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268133_listdate ON public.virtual_justice_clock USING btree (list_date);


--
-- Name: idx_8268140_flag; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268140_flag ON public.virtual_justice_clock_casetype USING btree (flag);


--
-- Name: idx_8268140_idd; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268140_idd ON public.virtual_justice_clock_casetype USING btree (id);


--
-- Name: idx_8268140_isactive; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268140_isactive ON public.virtual_justice_clock_casetype USING btree (is_active);


--
-- Name: idx_8268161_flag; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268161_flag ON public.virtual_justice_clock_main_subject_category USING btree (flag);


--
-- Name: idx_8268161_idd; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268161_idd ON public.virtual_justice_clock_main_subject_category USING btree (id);


--
-- Name: idx_8268161_isactive; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268161_isactive ON public.virtual_justice_clock_main_subject_category USING btree (is_active);


--
-- Name: idx_8268182_flag; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268182_flag ON public.virtual_justice_clock_scrutiny USING btree (flag);


--
-- Name: idx_8268182_idd; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268182_idd ON public.virtual_justice_clock_scrutiny USING btree (id);


--
-- Name: idx_8268182_isactive; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_8268182_isactive ON public.virtual_justice_clock_scrutiny USING btree (is_active);


--
-- Name: act_section on_update_current_timestamp; Type: TRIGGER; Schema: master; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON master.act_section FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_act_section();


--
-- Name: master_banks on_update_current_timestamp; Type: TRIGGER; Schema: master; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON master.master_banks FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_master_banks();


--
-- Name: t_category_master on_update_current_timestamp; Type: TRIGGER; Schema: master; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON master.t_category_master FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_t_category_master();


--
-- Name: t_doc_details on_update_current_timestamp; Type: TRIGGER; Schema: master; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON master.t_doc_details FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_t_doc_details();


--
-- Name: abr_accused on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.abr_accused FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_abr_accused();


--
-- Name: act_main on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.act_main FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_act_main();


--
-- Name: caveat_diary_matching on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.caveat_diary_matching FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_caveat_diary_matching();


--
-- Name: data_tentative_dates on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.data_tentative_dates FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_data_tentative_dates();


--
-- Name: data_tentative_dates_log on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.data_tentative_dates_log FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_data_tentative_dates_log();


--
-- Name: dispose on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.dispose FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_dispose();


--
-- Name: dispose_delete on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.dispose_delete FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_dispose_delete();


--
-- Name: docdetails on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.docdetails FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_docdetails();


--
-- Name: docdetails_history on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.docdetails_history FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_docdetails_history();


--
-- Name: fdr_records on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.fdr_records FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_fdr_records();


--
-- Name: main on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.main FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_main();


--
-- Name: main_backup_data_correction on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.main_backup_data_correction FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_main_backup_data_correction();


--
-- Name: main_casetype_history on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.main_casetype_history FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_main_casetype_history();


--
-- Name: mobile_numbers_wa on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.mobile_numbers_wa FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_mobile_numbers_wa();


--
-- Name: mul_category on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.mul_category FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_mul_category();


--
-- Name: njdg_act on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.njdg_act FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_njdg_act();


--
-- Name: njdg_category_transaction on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.njdg_category_transaction FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_njdg_category_transaction();


--
-- Name: njdg_lower_court on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.njdg_lower_court FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_njdg_lower_court();


--
-- Name: njdg_ordernet on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.njdg_ordernet FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_njdg_ordernet();


--
-- Name: njdg_ordernet_16102022 on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.njdg_ordernet_16102022 FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_njdg_ordernet_16102022();


--
-- Name: njdg_stats on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.njdg_stats FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_njdg_stats();


--
-- Name: njdg_transaction on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.njdg_transaction FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_njdg_transaction();


--
-- Name: njdg_transaction_bck_11102022 on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.njdg_transaction_bck_11102022 FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_njdg_transaction_bck_11102022();


--
-- Name: ordernet on_update_current_timestamp; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER on_update_current_timestamp BEFORE UPDATE ON public.ordernet FOR EACH ROW EXECUTE FUNCTION public.on_update_current_timestamp_ordernet();


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE USAGE ON SCHEMA public FROM PUBLIC;
GRANT CREATE ON SCHEMA public TO PUBLIC;
GRANT USAGE ON SCHEMA public TO dev;


--
-- Name: TABLE act_master; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.act_master TO dev;


--
-- Name: SEQUENCE act_master_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.act_master_id_seq TO dev;


--
-- Name: TABLE act_section; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.act_section TO dev;


--
-- Name: TABLE admin_icmis_usertype_map; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.admin_icmis_usertype_map TO dev;


--
-- Name: SEQUENCE admin_icmis_usertype_map_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.admin_icmis_usertype_map_id_seq TO dev;


--
-- Name: TABLE agency_master; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.agency_master TO dev;


--
-- Name: TABLE amicus_curiae_allotment_direction; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.amicus_curiae_allotment_direction TO dev;


--
-- Name: SEQUENCE amicus_curiae_allotment_direction_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.amicus_curiae_allotment_direction_id_seq TO dev;


--
-- Name: TABLE authority; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.authority TO dev;


--
-- Name: TABLE bar; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.bar TO dev;


--
-- Name: SEQUENCE bar_bar_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.bar_bar_id_seq TO dev;


--
-- Name: TABLE bench; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.bench TO dev;


--
-- Name: TABLE call_listing_days; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.call_listing_days TO dev;


--
-- Name: SEQUENCE call_listing_days_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.call_listing_days_id_seq TO dev;


--
-- Name: TABLE case_remarks_head; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.case_remarks_head TO dev;


--
-- Name: SEQUENCE case_remarks_head_sno_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.case_remarks_head_sno_seq TO dev;


--
-- Name: TABLE case_status_flag; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.case_status_flag TO dev;


--
-- Name: SEQUENCE case_status_flag_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.case_status_flag_id_seq TO dev;


--
-- Name: TABLE case_verify_by_sec_remark; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.case_verify_by_sec_remark TO dev;


--
-- Name: SEQUENCE case_verify_by_sec_remark_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.case_verify_by_sec_remark_id_seq TO dev;


--
-- Name: TABLE caselaw; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.caselaw TO dev;


--
-- Name: SEQUENCE caselaw_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.caselaw_id_seq TO dev;


--
-- Name: TABLE casetype; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.casetype TO dev;


--
-- Name: TABLE cat_jud_ratio; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.cat_jud_ratio TO dev;


--
-- Name: TABLE cnt_caveat; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.cnt_caveat TO dev;


--
-- Name: SEQUENCE cnt_caveat_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.cnt_caveat_id_seq TO dev;


--
-- Name: TABLE cnt_diary_no; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.cnt_diary_no TO dev;


--
-- Name: SEQUENCE cnt_diary_no_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.cnt_diary_no_id_seq TO dev;


--
-- Name: TABLE cnt_token; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.cnt_token TO dev;


--
-- Name: SEQUENCE cnt_token_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.cnt_token_id_seq TO dev;


--
-- Name: TABLE content_for_latestupdates; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.content_for_latestupdates TO dev;


--
-- Name: SEQUENCE content_for_latestupdates_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.content_for_latestupdates_id_seq TO dev;


--
-- Name: TABLE copy_category; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.copy_category TO dev;


--
-- Name: SEQUENCE copy_category_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.copy_category_id_seq TO dev;


--
-- Name: TABLE copying_reasons_for_rejection; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.copying_reasons_for_rejection TO dev;


--
-- Name: SEQUENCE copying_reasons_for_rejection_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.copying_reasons_for_rejection_id_seq TO dev;


--
-- Name: TABLE copying_role; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.copying_role TO dev;


--
-- Name: SEQUENCE copying_role_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.copying_role_id_seq TO dev;


--
-- Name: TABLE country; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.country TO dev;


--
-- Name: SEQUENCE country_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.country_id_seq TO dev;


--
-- Name: TABLE court_ip; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.court_ip TO dev;


--
-- Name: SEQUENCE court_ip_sno_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.court_ip_sno_seq TO dev;


--
-- Name: TABLE court_masters; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.court_masters TO dev;


--
-- Name: SEQUENCE court_masters_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.court_masters_id_seq TO dev;


--
-- Name: TABLE da_case_distribution; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.da_case_distribution TO dev;


--
-- Name: SEQUENCE da_case_distribution_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.da_case_distribution_id_seq TO dev;


--
-- Name: TABLE da_case_distribution_new; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.da_case_distribution_new TO dev;


--
-- Name: SEQUENCE da_case_distribution_new_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.da_case_distribution_new_id_seq TO dev;


--
-- Name: TABLE da_case_distribution_pilwrit; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.da_case_distribution_pilwrit TO dev;


--
-- Name: SEQUENCE da_case_distribution_pilwrit_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.da_case_distribution_pilwrit_id_seq TO dev;


--
-- Name: TABLE da_case_distribution_tri; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.da_case_distribution_tri TO dev;


--
-- Name: SEQUENCE da_case_distribution_tri_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.da_case_distribution_tri_id_seq TO dev;


--
-- Name: TABLE da_case_distribution_tri_new; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.da_case_distribution_tri_new TO dev;


--
-- Name: SEQUENCE da_case_distribution_tri_new_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.da_case_distribution_tri_new_id_seq TO dev;


--
-- Name: TABLE defect_policy; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.defect_policy TO dev;


--
-- Name: SEQUENCE defect_policy_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.defect_policy_id_seq TO dev;


--
-- Name: TABLE defect_record_paperbook; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.defect_record_paperbook TO dev;


--
-- Name: SEQUENCE defect_record_paperbook_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.defect_record_paperbook_id_seq TO dev;


--
-- Name: TABLE delhi_district_court; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.delhi_district_court TO dev;


--
-- Name: TABLE deptt; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.deptt TO dev;


--
-- Name: TABLE dev; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.dev TO dev;


--
-- Name: TABLE dev1; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.dev1 TO dev;


--
-- Name: TABLE disposal; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.disposal TO dev;


--
-- Name: TABLE district; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.district TO dev;


--
-- Name: TABLE dockount; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.dockount TO dev;


--
-- Name: TABLE docmaster; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.docmaster TO dev;


--
-- Name: TABLE drop_reason; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.drop_reason TO dev;


--
-- Name: SEQUENCE drop_reason_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.drop_reason_id_seq TO dev;


--
-- Name: TABLE ec_pil_reference_mapping; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ec_pil_reference_mapping TO dev;


--
-- Name: SEQUENCE ec_pil_reference_mapping_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ec_pil_reference_mapping_id_seq TO dev;


--
-- Name: TABLE education_type; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.education_type TO dev;


--
-- Name: SEQUENCE education_type_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.education_type_id_seq TO dev;


--
-- Name: TABLE emp_desg; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.emp_desg TO dev;


--
-- Name: TABLE emp_details_t; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.emp_details_t TO dev;


--
-- Name: TABLE escr_users; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.escr_users TO dev;


--
-- Name: SEQUENCE escr_users_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.escr_users_id_seq TO dev;


--
-- Name: TABLE event_master; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.event_master TO dev;


--
-- Name: SEQUENCE event_master_event_code_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.event_master_event_code_seq TO dev;


--
-- Name: TABLE godown_user_allocation; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.godown_user_allocation TO dev;


--
-- Name: TABLE holidays; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.holidays TO dev;


--
-- Name: TABLE icmis_faqs; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.icmis_faqs TO dev;


--
-- Name: SEQUENCE icmis_faqs_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.icmis_faqs_id_seq TO dev;


--
-- Name: TABLE id_proof_master; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.id_proof_master TO dev;


--
-- Name: SEQUENCE id_proof_master_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.id_proof_master_id_seq TO dev;


--
-- Name: TABLE initialization; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.initialization TO dev;


--
-- Name: TABLE intercasetype; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.intercasetype TO dev;


--
-- Name: TABLE intercasetype_new; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.intercasetype_new TO dev;


--
-- Name: TABLE jail_master; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.jail_master TO dev;


--
-- Name: TABLE judge; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.judge TO dev;


--
-- Name: TABLE judge_category; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.judge_category TO dev;


--
-- Name: SEQUENCE judge_category_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.judge_category_id_seq TO dev;


--
-- Name: TABLE judge_desg; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.judge_desg TO dev;


--
-- Name: SEQUENCE judge_desg_desgcode_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.judge_desg_desgcode_seq TO dev;


--
-- Name: TABLE kounter; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.kounter TO dev;


--
-- Name: SEQUENCE kounter_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.kounter_id_seq TO dev;


--
-- Name: TABLE law_firm; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.law_firm TO dev;


--
-- Name: TABLE law_firm_adv; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.law_firm_adv TO dev;


--
-- Name: SEQUENCE law_firm_adv_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.law_firm_adv_id_seq TO dev;


--
-- Name: SEQUENCE law_firm_law_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.law_firm_law_id_seq TO dev;


--
-- Name: TABLE lc_casetype; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.lc_casetype TO dev;


--
-- Name: TABLE lc_hc_casetype; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.lc_hc_casetype TO dev;


--
-- Name: SEQUENCE lc_hc_casetype_lccasecode_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.lc_hc_casetype_lccasecode_seq TO dev;


--
-- Name: TABLE listed_info; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.listed_info TO dev;


--
-- Name: SEQUENCE listed_info_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.listed_info_id_seq TO dev;


--
-- Name: TABLE listing_purpose; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.listing_purpose TO dev;


--
-- Name: TABLE m_court_fee; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.m_court_fee TO dev;


--
-- Name: SEQUENCE m_court_fee_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.m_court_fee_id_seq TO dev;


--
-- Name: TABLE m_court_fee_valuation; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.m_court_fee_valuation TO dev;


--
-- Name: SEQUENCE m_court_fee_valuation_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.m_court_fee_valuation_id_seq TO dev;


--
-- Name: TABLE m_from_court; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.m_from_court TO dev;


--
-- Name: SEQUENCE m_from_court_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.m_from_court_id_seq TO dev;


--
-- Name: TABLE m_limitation_period; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.m_limitation_period TO dev;


--
-- Name: SEQUENCE m_limitation_period_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.m_limitation_period_id_seq TO dev;


--
-- Name: TABLE m_to_r_casetype_mapping; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.m_to_r_casetype_mapping TO dev;


--
-- Name: SEQUENCE m_to_r_casetype_mapping_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.m_to_r_casetype_mapping_id_seq TO dev;


--
-- Name: TABLE main_report; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.main_report TO dev;


--
-- Name: SEQUENCE main_report_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.main_report_id_seq TO dev;


--
-- Name: TABLE master_banks; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.master_banks TO dev;


--
-- Name: SEQUENCE master_banks_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.master_banks_id_seq TO dev;


--
-- Name: TABLE master_bench; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.master_bench TO dev;


--
-- Name: TABLE master_board_type; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.master_board_type TO dev;


--
-- Name: TABLE master_case_status; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.master_case_status TO dev;


--
-- Name: TABLE master_court_complex; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.master_court_complex TO dev;


--
-- Name: TABLE master_fdstatus; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.master_fdstatus TO dev;


--
-- Name: SEQUENCE master_fdstatus_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.master_fdstatus_id_seq TO dev;


--
-- Name: TABLE master_fixedfor; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.master_fixedfor TO dev;


--
-- Name: SEQUENCE master_fixedfor_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.master_fixedfor_id_seq TO dev;


--
-- Name: TABLE master_list_type; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.master_list_type TO dev;


--
-- Name: SEQUENCE master_list_type_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.master_list_type_id_seq TO dev;


--
-- Name: TABLE master_main_supp; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.master_main_supp TO dev;


--
-- Name: TABLE master_module; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.master_module TO dev;


--
-- Name: SEQUENCE master_module_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.master_module_id_seq TO dev;


--
-- Name: TABLE master_stakeholder_type; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.master_stakeholder_type TO dev;


--
-- Name: SEQUENCE master_stakeholder_type_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.master_stakeholder_type_id_seq TO dev;


--
-- Name: TABLE media_persions; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.media_persions TO dev;


--
-- Name: SEQUENCE media_persions_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.media_persions_id_seq TO dev;


--
-- Name: TABLE menu_for_latestupdates; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.menu_for_latestupdates TO dev;


--
-- Name: SEQUENCE menu_for_latestupdates_mno_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.menu_for_latestupdates_mno_seq TO dev;


--
-- Name: TABLE menu_old; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.menu_old TO dev;


--
-- Name: SEQUENCE menu_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.menu_id_seq TO dev;


--
-- Name: TABLE mn_me_per; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.mn_me_per TO dev;


--
-- Name: SEQUENCE mn_me_per_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.mn_me_per_id_seq TO dev;


--
-- Name: TABLE module_table; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.module_table TO dev;


--
-- Name: SEQUENCE module_table_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.module_table_id_seq TO dev;


--
-- Name: TABLE national_case_type_revised; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.national_case_type_revised TO dev;


--
-- Name: TABLE national_code_for_acts; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.national_code_for_acts TO dev;


--
-- Name: TABLE national_code_judge; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.national_code_judge TO dev;


--
-- Name: TABLE national_disposal_type; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.national_disposal_type TO dev;


--
-- Name: TABLE national_purpose_listing_stage; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.national_purpose_listing_stage TO dev;


--
-- Name: TABLE not_before_reason; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.not_before_reason TO dev;


--
-- Name: SEQUENCE not_before_reason_res_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.not_before_reason_res_id_seq TO dev;


--
-- Name: TABLE notice_mapping; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.notice_mapping TO dev;


--
-- Name: SEQUENCE notice_mapping_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.notice_mapping_id_seq TO dev;


--
-- Name: TABLE ntl_judge; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ntl_judge TO dev;


--
-- Name: TABLE ntl_judge_category; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ntl_judge_category TO dev;


--
-- Name: TABLE ntl_judge_dept; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ntl_judge_dept TO dev;


--
-- Name: TABLE objection; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.objection TO dev;


--
-- Name: SEQUENCE objection_objcode_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.objection_objcode_seq TO dev;


--
-- Name: TABLE occupation_type; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.occupation_type TO dev;


--
-- Name: SEQUENCE occupation_type_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.occupation_type_id_seq TO dev;


--
-- Name: TABLE office_report_master; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.office_report_master TO dev;


--
-- Name: SEQUENCE office_report_master_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.office_report_master_id_seq TO dev;


--
-- Name: TABLE org_lower_court_judges; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.org_lower_court_judges TO dev;


--
-- Name: SEQUENCE org_lower_court_judges_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.org_lower_court_judges_id_seq TO dev;


--
-- Name: TABLE page_charges; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.page_charges TO dev;


--
-- Name: TABLE pending_type; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.pending_type TO dev;


--
-- Name: TABLE police; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.police TO dev;


--
-- Name: TABLE post_distance_master; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.post_distance_master TO dev;


--
-- Name: TABLE post_envelop_master; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.post_envelop_master TO dev;


--
-- Name: SEQUENCE post_envelop_master_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.post_envelop_master_id_seq TO dev;


--
-- Name: TABLE post_t; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.post_t TO dev;


--
-- Name: TABLE post_tariff_calc_master; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.post_tariff_calc_master TO dev;


--
-- Name: SEQUENCE post_tariff_calc_master_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.post_tariff_calc_master_id_seq TO dev;


--
-- Name: TABLE random_user; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.random_user TO dev;


--
-- Name: TABLE random_user_hc; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.random_user_hc TO dev;


--
-- Name: SEQUENCE random_user_hc_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.random_user_hc_id_seq TO dev;


--
-- Name: SEQUENCE random_user_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.random_user_id_seq TO dev;


--
-- Name: TABLE ref_agency_code; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_agency_code TO dev;


--
-- Name: SEQUENCE ref_agency_code_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_agency_code_id_seq TO dev;


--
-- Name: TABLE ref_agency_state; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_agency_state TO dev;


--
-- Name: SEQUENCE ref_agency_state_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_agency_state_id_seq TO dev;


--
-- Name: TABLE ref_city; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_city TO dev;


--
-- Name: TABLE ref_copying_source; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_copying_source TO dev;


--
-- Name: SEQUENCE ref_copying_source_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_copying_source_id_seq TO dev;


--
-- Name: TABLE ref_copying_status; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_copying_status TO dev;


--
-- Name: SEQUENCE ref_copying_status_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_copying_status_id_seq TO dev;


--
-- Name: TABLE ref_defect_code; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_defect_code TO dev;


--
-- Name: TABLE ref_faster_steps; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_faster_steps TO dev;


--
-- Name: SEQUENCE ref_faster_steps_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_faster_steps_id_seq TO dev;


--
-- Name: TABLE ref_file_movement_status; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_file_movement_status TO dev;


--
-- Name: SEQUENCE ref_file_movement_status_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_file_movement_status_id_seq TO dev;


--
-- Name: TABLE ref_items; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_items TO dev;


--
-- Name: SEQUENCE ref_items_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_items_id_seq TO dev;


--
-- Name: TABLE ref_keyword; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_keyword TO dev;


--
-- Name: SEQUENCE ref_keyword_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_keyword_id_seq TO dev;


--
-- Name: TABLE ref_letter_status; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_letter_status TO dev;


--
-- Name: SEQUENCE ref_letter_status_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_letter_status_id_seq TO dev;


--
-- Name: TABLE ref_lower_court_case_type; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_lower_court_case_type TO dev;


--
-- Name: TABLE ref_order_defect; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_order_defect TO dev;


--
-- Name: TABLE ref_order_type; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_order_type TO dev;


--
-- Name: TABLE ref_pil_action_taken; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_pil_action_taken TO dev;


--
-- Name: SEQUENCE ref_pil_action_taken_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_pil_action_taken_id_seq TO dev;


--
-- Name: TABLE ref_pil_category; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_pil_category TO dev;


--
-- Name: SEQUENCE ref_pil_category_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_pil_category_id_seq TO dev;


--
-- Name: TABLE ref_postal_type; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_postal_type TO dev;


--
-- Name: TABLE ref_rr_hall; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_rr_hall TO dev;


--
-- Name: SEQUENCE ref_rr_hall_hall_no_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_rr_hall_hall_no_seq TO dev;


--
-- Name: TABLE ref_special_category_filing; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_special_category_filing TO dev;


--
-- Name: SEQUENCE ref_special_category_filing_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_special_category_filing_id_seq TO dev;


--
-- Name: TABLE ref_state; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_state TO dev;


--
-- Name: SEQUENCE ref_state_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.ref_state_id_seq TO dev;


--
-- Name: TABLE ref_subject_category; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.ref_subject_category TO dev;


--
-- Name: TABLE role_master; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.role_master TO dev;


--
-- Name: SEQUENCE role_master_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.role_master_id_seq TO dev;


--
-- Name: TABLE role_menu_mapping; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.role_menu_mapping TO dev;


--
-- Name: TABLE role_menu_mapping_history; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.role_menu_mapping_history TO dev;


--
-- Name: SEQUENCE role_menu_mapping_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.role_menu_mapping_id_seq TO dev;


--
-- Name: TABLE roster; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.roster TO dev;


--
-- Name: TABLE roster_bench; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.roster_bench TO dev;


--
-- Name: SEQUENCE roster_bench_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.roster_bench_id_seq TO dev;


--
-- Name: TABLE roster_judge; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.roster_judge TO dev;


--
-- Name: SEQUENCE roster_judge_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.roster_judge_id_seq TO dev;


--
-- Name: TABLE rr_da_case_distribution; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.rr_da_case_distribution TO dev;


--
-- Name: SEQUENCE rr_da_case_distribution_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.rr_da_case_distribution_id_seq TO dev;


--
-- Name: TABLE rr_hall_case_distribution; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.rr_hall_case_distribution TO dev;


--
-- Name: SEQUENCE rr_hall_case_distribution_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.rr_hall_case_distribution_id_seq TO dev;


--
-- Name: TABLE rr_user_hall_mapping; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.rr_user_hall_mapping TO dev;


--
-- Name: SEQUENCE rr_user_hall_mapping_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.rr_user_hall_mapping_id_seq TO dev;


--
-- Name: TABLE rto; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.rto TO dev;


--
-- Name: SEQUENCE rto_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.rto_id_seq TO dev;


--
-- Name: TABLE sc_working_days; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.sc_working_days TO dev;


--
-- Name: SEQUENCE sc_working_days_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.sc_working_days_id_seq TO dev;


--
-- Name: TABLE sensitive_case_users; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.sensitive_case_users TO dev;


--
-- Name: SEQUENCE sensitive_case_users_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.sensitive_case_users_id_seq TO dev;


--
-- Name: TABLE similarity_remarks; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.similarity_remarks TO dev;


--
-- Name: SEQUENCE similarity_remarks_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.similarity_remarks_id_seq TO dev;


--
-- Name: TABLE single_judge_nominate; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.single_judge_nominate TO dev;


--
-- Name: SEQUENCE single_judge_nominate_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.single_judge_nominate_id_seq TO dev;


--
-- Name: TABLE sitting_plan_court_details; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.sitting_plan_court_details TO dev;


--
-- Name: SEQUENCE sitting_plan_court_details_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.sitting_plan_court_details_id_seq TO dev;


--
-- Name: TABLE sitting_plan_details; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.sitting_plan_details TO dev;


--
-- Name: SEQUENCE sitting_plan_details_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.sitting_plan_details_id_seq TO dev;


--
-- Name: TABLE sitting_plan_judges_details; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.sitting_plan_judges_details TO dev;


--
-- Name: SEQUENCE sitting_plan_judges_details_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.sitting_plan_judges_details_id_seq TO dev;


--
-- Name: TABLE sitting_plan_judges_leave_details; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.sitting_plan_judges_leave_details TO dev;


--
-- Name: SEQUENCE sitting_plan_judges_leave_details_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.sitting_plan_judges_leave_details_id_seq TO dev;


--
-- Name: TABLE specific_role; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.specific_role TO dev;


--
-- Name: SEQUENCE specific_role_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.specific_role_id_seq TO dev;


--
-- Name: TABLE stakeholder_details; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.stakeholder_details TO dev;


--
-- Name: SEQUENCE stakeholder_details_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.stakeholder_details_id_seq TO dev;


--
-- Name: TABLE stampreg; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.stampreg TO dev;


--
-- Name: TABLE state; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.state TO dev;


--
-- Name: SEQUENCE state_id_no_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.state_id_no_seq TO dev;


--
-- Name: TABLE sub_me_per; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.sub_me_per TO dev;


--
-- Name: SEQUENCE sub_me_per_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.sub_me_per_id_seq TO dev;


--
-- Name: TABLE sub_report; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.sub_report TO dev;


--
-- Name: SEQUENCE sub_report_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.sub_report_id_seq TO dev;


--
-- Name: TABLE sub_sub_me_per; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.sub_sub_me_per TO dev;


--
-- Name: SEQUENCE sub_sub_me_per_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.sub_sub_me_per_id_seq TO dev;


--
-- Name: TABLE sub_sub_menu; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.sub_sub_menu TO dev;


--
-- Name: SEQUENCE sub_sub_menu_su_su_menu_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.sub_sub_menu_su_su_menu_id_seq TO dev;


--
-- Name: TABLE subheading; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.subheading TO dev;


--
-- Name: TABLE submaster; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.submaster TO dev;


--
-- Name: TABLE submenu; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.submenu TO dev;


--
-- Name: SEQUENCE submenu_su_menu_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.submenu_su_menu_id_seq TO dev;


--
-- Name: TABLE t_category_master; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.t_category_master TO dev;


--
-- Name: SEQUENCE t_category_master_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.t_category_master_id_seq TO dev;


--
-- Name: TABLE t_doc_details; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.t_doc_details TO dev;


--
-- Name: TABLE tbl_user; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.tbl_user TO dev;


--
-- Name: TABLE tbl_usercode_changed; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.tbl_usercode_changed TO dev;


--
-- Name: TABLE token_status; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.token_status TO dev;


--
-- Name: TABLE tribunal; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.tribunal TO dev;


--
-- Name: TABLE tw_max_process; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.tw_max_process TO dev;


--
-- Name: SEQUENCE tw_max_process_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.tw_max_process_id_seq TO dev;


--
-- Name: TABLE tw_notice; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.tw_notice TO dev;


--
-- Name: SEQUENCE tw_notice_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.tw_notice_id_seq TO dev;


--
-- Name: TABLE tw_pf_his; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.tw_pf_his TO dev;


--
-- Name: SEQUENCE tw_pf_his_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.tw_pf_his_id_seq TO dev;


--
-- Name: TABLE tw_pin_code; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.tw_pin_code TO dev;


--
-- Name: SEQUENCE tw_pin_code_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.tw_pin_code_id_seq TO dev;


--
-- Name: TABLE tw_section; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.tw_section TO dev;


--
-- Name: SEQUENCE tw_section_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.tw_section_id_seq TO dev;


--
-- Name: TABLE tw_send_to; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.tw_send_to TO dev;


--
-- Name: SEQUENCE tw_send_to_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.tw_send_to_id_seq TO dev;


--
-- Name: TABLE tw_serve; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.tw_serve TO dev;


--
-- Name: SEQUENCE tw_serve_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.tw_serve_id_seq TO dev;


--
-- Name: TABLE tw_weight_or; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.tw_weight_or TO dev;


--
-- Name: SEQUENCE tw_weight_or_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.tw_weight_or_id_seq TO dev;


--
-- Name: TABLE user_d_t_map; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.user_d_t_map TO dev;


--
-- Name: SEQUENCE user_d_t_map_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.user_d_t_map_id_seq TO dev;


--
-- Name: TABLE user_l_map; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.user_l_map TO dev;


--
-- Name: SEQUENCE user_l_map_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.user_l_map_id_seq TO dev;


--
-- Name: TABLE user_l_type; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.user_l_type TO dev;


--
-- Name: SEQUENCE user_l_type_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.user_l_type_id_seq TO dev;


--
-- Name: TABLE user_range; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.user_range TO dev;


--
-- Name: SEQUENCE user_range_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.user_range_id_seq TO dev;


--
-- Name: TABLE user_role_master_mapping; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.user_role_master_mapping TO dev;


--
-- Name: TABLE user_role_master_mapping_history; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.user_role_master_mapping_history TO dev;


--
-- Name: SEQUENCE user_role_master_mapping_history_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.user_role_master_mapping_history_id_seq TO dev;


--
-- Name: SEQUENCE user_role_master_mapping_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.user_role_master_mapping_id_seq TO dev;


--
-- Name: TABLE user_sec_map; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.user_sec_map TO dev;


--
-- Name: SEQUENCE user_sec_map_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.user_sec_map_id_seq TO dev;


--
-- Name: TABLE userdept; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.userdept TO dev;


--
-- Name: SEQUENCE userdept_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.userdept_id_seq TO dev;


--
-- Name: TABLE users; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.users TO dev;


--
-- Name: SEQUENCE users_usercode_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.users_usercode_seq TO dev;


--
-- Name: TABLE usersection; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.usersection TO dev;


--
-- Name: SEQUENCE usersection_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.usersection_id_seq TO dev;


--
-- Name: TABLE usertype; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.usertype TO dev;


--
-- Name: SEQUENCE usertype_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.usertype_id_seq TO dev;


--
-- Name: TABLE vernacular_languages; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON TABLE master.vernacular_languages TO dev;


--
-- Name: SEQUENCE vernacular_languages_id_seq; Type: ACL; Schema: master; Owner: postgres
--

GRANT ALL ON SEQUENCE master.vernacular_languages_id_seq TO dev;


--
-- Name: TABLE a_series; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.a_series TO dev;


--
-- Name: TABLE abr_accused; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.abr_accused TO dev;


--
-- Name: TABLE ac; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ac TO dev;


--
-- Name: SEQUENCE ac_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ac_id_seq TO dev;


--
-- Name: TABLE act_main; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.act_main TO dev;


--
-- Name: SEQUENCE act_main_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.act_main_id_seq TO dev;


--
-- Name: TABLE admin; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.admin TO dev;


--
-- Name: SEQUENCE admin_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.admin_id_seq TO dev;


--
-- Name: TABLE admin_user_permission; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.admin_user_permission TO dev;


--
-- Name: SEQUENCE admin_user_permission_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.admin_user_permission_id_seq TO dev;


--
-- Name: TABLE admin_user_roles; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.admin_user_roles TO dev;


--
-- Name: TABLE admin_usr_roles_permission; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.admin_usr_roles_permission TO dev;


--
-- Name: TABLE advance_allocated; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.advance_allocated TO dev;


--
-- Name: SEQUENCE advance_allocated_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.advance_allocated_id_seq TO dev;


--
-- Name: TABLE advance_cl_printed; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.advance_cl_printed TO dev;


--
-- Name: SEQUENCE advance_cl_printed_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.advance_cl_printed_id_seq TO dev;


--
-- Name: TABLE advance_elimination_cl_printed; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.advance_elimination_cl_printed TO dev;


--
-- Name: SEQUENCE advance_elimination_cl_printed_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.advance_elimination_cl_printed_id_seq TO dev;


--
-- Name: TABLE advance_single_judge_allocated; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.advance_single_judge_allocated TO dev;


--
-- Name: SEQUENCE advance_single_judge_allocated_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.advance_single_judge_allocated_id_seq TO dev;


--
-- Name: TABLE advance_single_judge_allocated_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.advance_single_judge_allocated_log TO dev;


--
-- Name: TABLE advanced_drop_note; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.advanced_drop_note TO dev;


--
-- Name: SEQUENCE advanced_drop_note_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.advanced_drop_note_id_seq TO dev;


--
-- Name: TABLE advocate; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.advocate TO dev;


--
-- Name: TABLE advocate_requisition_request; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.advocate_requisition_request TO dev;


--
-- Name: SEQUENCE advocate_requisition_request_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.advocate_requisition_request_id_seq TO dev;


--
-- Name: TABLE all_reg_no; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.all_reg_no TO dev;


--
-- Name: TABLE allocation_trap; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.allocation_trap TO dev;


--
-- Name: SEQUENCE allocation_trap_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.allocation_trap_id_seq TO dev;


--
-- Name: TABLE amicus_curiae; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.amicus_curiae TO dev;


--
-- Name: SEQUENCE amicus_curiae_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.amicus_curiae_id_seq TO dev;


--
-- Name: TABLE aor_clerk_trainee; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.aor_clerk_trainee TO dev;


--
-- Name: SEQUENCE aor_clerk_trainee_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.aor_clerk_trainee_id_seq TO dev;


--
-- Name: TABLE auto_coram_allottment; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.auto_coram_allottment TO dev;


--
-- Name: TABLE avi; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.avi TO dev;


--
-- Name: TABLE brdrem; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.brdrem TO dev;


--
-- Name: TABLE brdrem_his; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.brdrem_his TO dev;


--
-- Name: TABLE bulk_dismissal_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.bulk_dismissal_log TO dev;


--
-- Name: SEQUENCE bulk_dismissal_log_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.bulk_dismissal_log_id_seq TO dev;


--
-- Name: TABLE call_listing1_days; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.call_listing1_days TO dev;


--
-- Name: SEQUENCE call_listing1_days_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.call_listing1_days_id_seq TO dev;


--
-- Name: TABLE case_defect; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.case_defect TO dev;


--
-- Name: TABLE case_distribution_trap; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.case_distribution_trap TO dev;


--
-- Name: SEQUENCE case_distribution_trap_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.case_distribution_trap_id_seq TO dev;


--
-- Name: TABLE case_info; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.case_info TO dev;


--
-- Name: SEQUENCE case_info_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.case_info_id_seq TO dev;


--
-- Name: TABLE case_limit; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.case_limit TO dev;


--
-- Name: SEQUENCE case_limit_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.case_limit_id_seq TO dev;


--
-- Name: TABLE case_remarks_multiple; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.case_remarks_multiple TO dev;


--
-- Name: TABLE case_remarks_multiple_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.case_remarks_multiple_history TO dev;


--
-- Name: TABLE case_remarks_verification; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.case_remarks_verification TO dev;


--
-- Name: SEQUENCE case_remarks_verification_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.case_remarks_verification_id_seq TO dev;


--
-- Name: TABLE case_section_mapping; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.case_section_mapping TO dev;


--
-- Name: TABLE case_verify; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.case_verify TO dev;


--
-- Name: TABLE case_verify_by_sec; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.case_verify_by_sec TO dev;


--
-- Name: SEQUENCE case_verify_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.case_verify_id_seq TO dev;


--
-- Name: TABLE case_verify_rop; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.case_verify_rop TO dev;


--
-- Name: SEQUENCE case_verify_rop_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.case_verify_rop_id_seq TO dev;


--
-- Name: TABLE category_allottment; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.category_allottment TO dev;


--
-- Name: SEQUENCE category_allottment_cat_allot_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.category_allottment_cat_allot_id_seq TO dev;


--
-- Name: TABLE cause_title; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.cause_title TO dev;


--
-- Name: SEQUENCE cause_title_cause_title_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.cause_title_cause_title_id_seq TO dev;


--
-- Name: TABLE causelist_file_movement; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.causelist_file_movement TO dev;


--
-- Name: SEQUENCE causelist_file_movement_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.causelist_file_movement_id_seq TO dev;


--
-- Name: TABLE causelist_file_movement_transactions; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.causelist_file_movement_transactions TO dev;


--
-- Name: SEQUENCE causelist_file_movement_transactions_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.causelist_file_movement_transactions_id_seq TO dev;


--
-- Name: TABLE caveat; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.caveat TO dev;


--
-- Name: TABLE caveat_adv; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.caveat_adv TO dev;


--
-- Name: TABLE caveat_advocate; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.caveat_advocate TO dev;


--
-- Name: TABLE caveat_diary_matching; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.caveat_diary_matching TO dev;


--
-- Name: TABLE caveat_diary_matching_new; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.caveat_diary_matching_new TO dev;


--
-- Name: TABLE caveat_lowerct; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.caveat_lowerct TO dev;


--
-- Name: TABLE caveat_lowerct_judges; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.caveat_lowerct_judges TO dev;


--
-- Name: SEQUENCE caveat_lowerct_judges_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.caveat_lowerct_judges_id_seq TO dev;


--
-- Name: SEQUENCE caveat_lowerct_lower_court_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.caveat_lowerct_lower_court_id_seq TO dev;


--
-- Name: TABLE caveat_party; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.caveat_party TO dev;


--
-- Name: TABLE change_fil_dt; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.change_fil_dt TO dev;


--
-- Name: TABLE chk_case; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.chk_case TO dev;


--
-- Name: SEQUENCE chk_case_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.chk_case_id_seq TO dev;


--
-- Name: TABLE cl_freezed; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.cl_freezed TO dev;


--
-- Name: SEQUENCE cl_freezed_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.cl_freezed_id_seq TO dev;


--
-- Name: TABLE cl_gen; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.cl_gen TO dev;


--
-- Name: SEQUENCE cl_gen_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.cl_gen_id_seq TO dev;


--
-- Name: TABLE cl_printed; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.cl_printed TO dev;


--
-- Name: SEQUENCE cl_printed_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.cl_printed_id_seq TO dev;


--
-- Name: TABLE cl_text_save; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.cl_text_save TO dev;


--
-- Name: TABLE conct; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.conct TO dev;


--
-- Name: TABLE conct_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.conct_history TO dev;


--
-- Name: TABLE consent_through_email; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.consent_through_email TO dev;


--
-- Name: SEQUENCE consent_through_email_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.consent_through_email_id_seq TO dev;


--
-- Name: TABLE copying_application_defects; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.copying_application_defects TO dev;


--
-- Name: SEQUENCE copying_application_defects_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.copying_application_defects_id_seq TO dev;


--
-- Name: TABLE copying_application_defects_org; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.copying_application_defects_org TO dev;


--
-- Name: SEQUENCE copying_application_defects_org_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.copying_application_defects_org_id_seq TO dev;


--
-- Name: TABLE copying_application_documents; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.copying_application_documents TO dev;


--
-- Name: SEQUENCE copying_application_documents_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.copying_application_documents_id_seq TO dev;


--
-- Name: TABLE copying_application_documents_org; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.copying_application_documents_org TO dev;


--
-- Name: SEQUENCE copying_application_documents_org_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.copying_application_documents_org_id_seq TO dev;


--
-- Name: TABLE copying_order_issuing_application_new; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.copying_order_issuing_application_new TO dev;


--
-- Name: TABLE copying_order_issuing_application_new_duplicate; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.copying_order_issuing_application_new_duplicate TO dev;


--
-- Name: SEQUENCE copying_order_issuing_application_new_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.copying_order_issuing_application_new_id_seq TO dev;


--
-- Name: TABLE copying_order_issuing_application_new_org; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.copying_order_issuing_application_new_org TO dev;


--
-- Name: SEQUENCE copying_order_issuing_application_new_org_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.copying_order_issuing_application_new_org_id_seq TO dev;


--
-- Name: TABLE copying_request_movement; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.copying_request_movement TO dev;


--
-- Name: SEQUENCE copying_request_movement_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.copying_request_movement_id_seq TO dev;


--
-- Name: TABLE copying_request_verify; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.copying_request_verify TO dev;


--
-- Name: TABLE copying_request_verify_documents; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.copying_request_verify_documents TO dev;


--
-- Name: SEQUENCE copying_request_verify_documents_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.copying_request_verify_documents_id_seq TO dev;


--
-- Name: TABLE copying_request_verify_documents_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.copying_request_verify_documents_log TO dev;


--
-- Name: SEQUENCE copying_request_verify_documents_log_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.copying_request_verify_documents_log_id_seq TO dev;


--
-- Name: SEQUENCE copying_request_verify_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.copying_request_verify_id_seq TO dev;


--
-- Name: TABLE copying_trap; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.copying_trap TO dev;


--
-- Name: SEQUENCE copying_trap_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.copying_trap_id_seq TO dev;


--
-- Name: TABLE coram; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.coram TO dev;


--
-- Name: TABLE court_ip_06012022; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.court_ip_06012022 TO dev;


--
-- Name: TABLE craent; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.craent TO dev;


--
-- Name: SEQUENCE craent_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.craent_id_seq TO dev;


--
-- Name: TABLE criminal_matters_category_new; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.criminal_matters_category_new TO dev;


--
-- Name: TABLE dashboard_data; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.dashboard_data TO dev;


--
-- Name: SEQUENCE dashboard_data_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.dashboard_data_id_seq TO dev;


--
-- Name: TABLE dashboard_data_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.dashboard_data_log TO dev;


--
-- Name: TABLE data_tentative_dates; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.data_tentative_dates TO dev;


--
-- Name: SEQUENCE data_tentative_dates_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.data_tentative_dates_id_seq TO dev;


--
-- Name: TABLE data_tentative_dates_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.data_tentative_dates_log TO dev;


--
-- Name: TABLE defect_case_list_26032019; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.defect_case_list_26032019 TO dev;


--
-- Name: SEQUENCE defect_case_list_26032019_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.defect_case_list_26032019_id_seq TO dev;


--
-- Name: TABLE defective_chamber_listing; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.defective_chamber_listing TO dev;


--
-- Name: SEQUENCE defective_chamber_listing_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.defective_chamber_listing_id_seq TO dev;


--
-- Name: TABLE defects_notified_mails; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.defects_notified_mails TO dev;


--
-- Name: SEQUENCE defects_notified_mails_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.defects_notified_mails_id_seq TO dev;


--
-- Name: TABLE defects_verification; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.defects_verification TO dev;


--
-- Name: TABLE defects_verification_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.defects_verification_history TO dev;


--
-- Name: SEQUENCE defects_verification_history_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.defects_verification_history_id_seq TO dev;


--
-- Name: SEQUENCE defects_verification_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.defects_verification_id_seq TO dev;


--
-- Name: TABLE diary_copy_set; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.diary_copy_set TO dev;


--
-- Name: SEQUENCE diary_copy_set_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.diary_copy_set_id_seq TO dev;


--
-- Name: TABLE diary_movement; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.diary_movement TO dev;


--
-- Name: TABLE diary_movement_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.diary_movement_history TO dev;


--
-- Name: SEQUENCE diary_movement_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.diary_movement_id_seq TO dev;


--
-- Name: TABLE digital_certification_details; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.digital_certification_details TO dev;


--
-- Name: SEQUENCE digital_certification_details_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.digital_certification_details_id_seq TO dev;


--
-- Name: TABLE dispose; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.dispose TO dev;


--
-- Name: TABLE dispose_delete; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.dispose_delete TO dev;


--
-- Name: TABLE docdetails; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.docdetails TO dev;


--
-- Name: SEQUENCE docdetails_docd_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.docdetails_docd_id_seq TO dev;


--
-- Name: TABLE docdetails_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.docdetails_history TO dev;


--
-- Name: TABLE docdetails_remark; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.docdetails_remark TO dev;


--
-- Name: TABLE docdetails_uploaded_documents; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.docdetails_uploaded_documents TO dev;


--
-- Name: SEQUENCE docdetails_uploaded_documents_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.docdetails_uploaded_documents_id_seq TO dev;


--
-- Name: TABLE docdetails_uploaded_documents_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.docdetails_uploaded_documents_log TO dev;


--
-- Name: SEQUENCE docdetails_uploaded_documents_log_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.docdetails_uploaded_documents_log_id_seq TO dev;


--
-- Name: TABLE draft_list; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.draft_list TO dev;


--
-- Name: TABLE drop_note; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.drop_note TO dev;


--
-- Name: SEQUENCE drop_note_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.drop_note_id_seq TO dev;


--
-- Name: TABLE duplicate_reg_no; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.duplicate_reg_no TO dev;


--
-- Name: TABLE ec_forward_letter_images; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_forward_letter_images TO dev;


--
-- Name: SEQUENCE ec_forward_letter_images_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ec_forward_letter_images_id_seq TO dev;


--
-- Name: TABLE ec_forward_letter_postal_transactions; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_forward_letter_postal_transactions TO dev;


--
-- Name: TABLE ec_keyword; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_keyword TO dev;


--
-- Name: SEQUENCE ec_keyword_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ec_keyword_id_seq TO dev;


--
-- Name: TABLE ec_pil; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_pil TO dev;


--
-- Name: TABLE ec_pil_group_file; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_pil_group_file TO dev;


--
-- Name: SEQUENCE ec_pil_group_file_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ec_pil_group_file_id_seq TO dev;


--
-- Name: SEQUENCE ec_pil_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ec_pil_id_seq TO dev;


--
-- Name: TABLE ec_pil_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_pil_log TO dev;


--
-- Name: TABLE ec_postal_dispatch; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_postal_dispatch TO dev;


--
-- Name: TABLE ec_postal_dispatch_connected_letters; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_postal_dispatch_connected_letters TO dev;


--
-- Name: TABLE ec_postal_dispatch_connected_letters_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_postal_dispatch_connected_letters_history TO dev;


--
-- Name: SEQUENCE ec_postal_dispatch_connected_letters_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ec_postal_dispatch_connected_letters_id_seq TO dev;


--
-- Name: SEQUENCE ec_postal_dispatch_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ec_postal_dispatch_id_seq TO dev;


--
-- Name: TABLE ec_postal_dispatch_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_postal_dispatch_log TO dev;


--
-- Name: TABLE ec_postal_dispatch_transactions; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_postal_dispatch_transactions TO dev;


--
-- Name: SEQUENCE ec_postal_dispatch_transactions_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ec_postal_dispatch_transactions_id_seq TO dev;


--
-- Name: TABLE ec_postal_received; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_postal_received TO dev;


--
-- Name: SEQUENCE ec_postal_received_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ec_postal_received_id_seq TO dev;


--
-- Name: TABLE ec_postal_received_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_postal_received_log TO dev;


--
-- Name: TABLE ec_postal_transactions; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_postal_transactions TO dev;


--
-- Name: SEQUENCE ec_postal_transactions_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ec_postal_transactions_id_seq TO dev;


--
-- Name: TABLE ec_postal_user_initiated_letter; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ec_postal_user_initiated_letter TO dev;


--
-- Name: SEQUENCE ec_postal_user_initiated_letter_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ec_postal_user_initiated_letter_id_seq TO dev;


--
-- Name: TABLE efiled_cases; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.efiled_cases TO dev;


--
-- Name: TABLE efiled_cases_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.efiled_cases_history TO dev;


--
-- Name: SEQUENCE efiled_cases_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.efiled_cases_id_seq TO dev;


--
-- Name: TABLE efiled_cases_transfer_status; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.efiled_cases_transfer_status TO dev;


--
-- Name: SEQUENCE efiled_cases_transfer_status_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.efiled_cases_transfer_status_id_seq TO dev;


--
-- Name: TABLE efiled_docs; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.efiled_docs TO dev;


--
-- Name: SEQUENCE efiled_docs_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.efiled_docs_id_seq TO dev;


--
-- Name: TABLE efiled_pdfs; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.efiled_pdfs TO dev;


--
-- Name: SEQUENCE efiled_pdfs_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.efiled_pdfs_id_seq TO dev;


--
-- Name: TABLE efiling_mails; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.efiling_mails TO dev;


--
-- Name: SEQUENCE efiling_mails_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.efiling_mails_id_seq TO dev;


--
-- Name: TABLE eliminated_cases; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.eliminated_cases TO dev;


--
-- Name: TABLE elimination; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.elimination TO dev;


--
-- Name: SEQUENCE elimination_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.elimination_id_seq TO dev;


--
-- Name: TABLE email_entire_list; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.email_entire_list TO dev;


--
-- Name: TABLE email_hc_cl; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.email_hc_cl TO dev;


--
-- Name: TABLE email_hc_cl_17042023; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.email_hc_cl_17042023 TO dev;


--
-- Name: TABLE f_1; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.f_1 TO dev;


--
-- Name: TABLE f_2; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.f_2 TO dev;


--
-- Name: TABLE faster_cases; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.faster_cases TO dev;


--
-- Name: SEQUENCE faster_cases_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.faster_cases_id_seq TO dev;


--
-- Name: TABLE faster_communication_details; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.faster_communication_details TO dev;


--
-- Name: SEQUENCE faster_communication_details_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.faster_communication_details_id_seq TO dev;


--
-- Name: TABLE faster_opted; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.faster_opted TO dev;


--
-- Name: SEQUENCE faster_opted_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.faster_opted_id_seq TO dev;


--
-- Name: TABLE faster_shared_document_details; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.faster_shared_document_details TO dev;


--
-- Name: SEQUENCE faster_shared_document_details_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.faster_shared_document_details_id_seq TO dev;


--
-- Name: TABLE faster_transactions; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.faster_transactions TO dev;


--
-- Name: SEQUENCE faster_transactions_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.faster_transactions_id_seq TO dev;


--
-- Name: TABLE fdr_records; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.fdr_records TO dev;


--
-- Name: SEQUENCE fdr_records_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.fdr_records_id_seq TO dev;


--
-- Name: TABLE fh_temp_for_srno_15_05_2024; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.fh_temp_for_srno_15_05_2024 TO dev;


--
-- Name: TABLE fil_no_fh_cases_updation; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.fil_no_fh_cases_updation TO dev;


--
-- Name: TABLE fil_trap; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.fil_trap TO dev;


--
-- Name: TABLE fil_trap_his; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.fil_trap_his TO dev;


--
-- Name: SEQUENCE fil_trap_his_uid_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.fil_trap_his_uid_seq TO dev;


--
-- Name: TABLE fil_trap_refil_users; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.fil_trap_refil_users TO dev;


--
-- Name: SEQUENCE fil_trap_refil_users_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.fil_trap_refil_users_id_seq TO dev;


--
-- Name: TABLE fil_trap_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.fil_trap_seq TO dev;


--
-- Name: SEQUENCE fil_trap_seq_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.fil_trap_seq_id_seq TO dev;


--
-- Name: SEQUENCE fil_trap_uid_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.fil_trap_uid_seq TO dev;


--
-- Name: TABLE fil_trap_users; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.fil_trap_users TO dev;


--
-- Name: SEQUENCE fil_trap_users_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.fil_trap_users_id_seq TO dev;


--
-- Name: TABLE filing_remark; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.filing_remark TO dev;


--
-- Name: SEQUENCE filing_remark_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.filing_remark_id_seq TO dev;


--
-- Name: TABLE filing_stats; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.filing_stats TO dev;


--
-- Name: SEQUENCE filing_stats_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.filing_stats_id_seq TO dev;


--
-- Name: TABLE final_elimination_cl_printed; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.final_elimination_cl_printed TO dev;


--
-- Name: SEQUENCE final_elimination_cl_printed_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.final_elimination_cl_printed_id_seq TO dev;


--
-- Name: TABLE free_text_rop; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.free_text_rop TO dev;


--
-- Name: TABLE headfooter; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.headfooter TO dev;


--
-- Name: SEQUENCE headfooter_hf_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.headfooter_hf_id_seq TO dev;


--
-- Name: TABLE heardt; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.heardt TO dev;


--
-- Name: TABLE heardt_webuse; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.heardt_webuse TO dev;


--
-- Name: TABLE hybrid_physical_hearing_consent; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.hybrid_physical_hearing_consent TO dev;


--
-- Name: TABLE hybrid_physical_hearing_consent_freeze; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.hybrid_physical_hearing_consent_freeze TO dev;


--
-- Name: SEQUENCE hybrid_physical_hearing_consent_freeze_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.hybrid_physical_hearing_consent_freeze_id_seq TO dev;


--
-- Name: SEQUENCE hybrid_physical_hearing_consent_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.hybrid_physical_hearing_consent_id_seq TO dev;


--
-- Name: TABLE hybrid_physical_hearing_consent_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.hybrid_physical_hearing_consent_log TO dev;


--
-- Name: TABLE i1; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.i1 TO dev;


--
-- Name: TABLE i2; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.i2 TO dev;


--
-- Name: TABLE ia_restore_remarks; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ia_restore_remarks TO dev;


--
-- Name: TABLE idp_stats; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.idp_stats TO dev;


--
-- Name: SEQUENCE idp_stats_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.idp_stats_id_seq TO dev;


--
-- Name: TABLE indexing; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.indexing TO dev;


--
-- Name: SEQUENCE indexing_ind_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.indexing_ind_id_seq TO dev;


--
-- Name: TABLE invalid_disp_dt_28072018; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.invalid_disp_dt_28072018 TO dev;


--
-- Name: TABLE jail_petition_details; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.jail_petition_details TO dev;


--
-- Name: SEQUENCE jail_petition_details_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.jail_petition_details_id_seq TO dev;


--
-- Name: TABLE jo_alottment_paps; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.jo_alottment_paps TO dev;


--
-- Name: TABLE judge_group; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.judge_group TO dev;


--
-- Name: TABLE judge_group1; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.judge_group1 TO dev;


--
-- Name: SEQUENCE judge_group_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.judge_group_id_seq TO dev;


--
-- Name: TABLE judgment_sci1; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.judgment_sci1 TO dev;


--
-- Name: TABLE judgment_summary; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.judgment_summary TO dev;


--
-- Name: SEQUENCE judgment_summary_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.judgment_summary_id_seq TO dev;


--
-- Name: TABLE judgment_summary_old; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.judgment_summary_old TO dev;


--
-- Name: TABLE jumped_filno; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.jumped_filno TO dev;


--
-- Name: SEQUENCE jumped_filno_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.jumped_filno_id_seq TO dev;


--
-- Name: TABLE kept_below; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.kept_below TO dev;


--
-- Name: TABLE last_heardt; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.last_heardt TO dev;


--
-- Name: TABLE last_heardt_webuse; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.last_heardt_webuse TO dev;


--
-- Name: TABLE law_points; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.law_points TO dev;


--
-- Name: SEQUENCE law_points_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.law_points_id_seq TO dev;


--
-- Name: TABLE lct_record_dis_rec; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.lct_record_dis_rec TO dev;


--
-- Name: SEQUENCE lct_record_dis_rec_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.lct_record_dis_rec_id_seq TO dev;


--
-- Name: TABLE ld_move; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ld_move TO dev;


--
-- Name: TABLE ld_move_29102018; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ld_move_29102018 TO dev;


--
-- Name: TABLE ld_move_30102018; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ld_move_30102018 TO dev;


--
-- Name: TABLE linked_cases; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.linked_cases TO dev;


--
-- Name: SEQUENCE linked_cases_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.linked_cases_id_seq TO dev;


--
-- Name: TABLE log_check; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.log_check TO dev;


--
-- Name: TABLE loose_block; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.loose_block TO dev;


--
-- Name: SEQUENCE loose_block_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.loose_block_id_seq TO dev;


--
-- Name: TABLE lowerct; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.lowerct TO dev;


--
-- Name: TABLE lowerct_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.lowerct_history TO dev;


--
-- Name: TABLE lowerct_judges; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.lowerct_judges TO dev;


--
-- Name: SEQUENCE lowerct_judges_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.lowerct_judges_id_seq TO dev;


--
-- Name: SEQUENCE lowerct_lower_court_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.lowerct_lower_court_id_seq TO dev;


--
-- Name: TABLE main; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.main TO dev;


--
-- Name: TABLE main_backup_data_correction; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.main_backup_data_correction TO dev;


--
-- Name: TABLE main_cancel_reg; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.main_cancel_reg TO dev;


--
-- Name: TABLE main_case_diplay_changes; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.main_case_diplay_changes TO dev;


--
-- Name: TABLE main_casetype_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.main_casetype_history TO dev;


--
-- Name: TABLE main_casetype_history_backup_data_correction; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.main_casetype_history_backup_data_correction TO dev;


--
-- Name: SEQUENCE main_casetype_history_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.main_casetype_history_id_seq TO dev;


--
-- Name: TABLE main_deleted_cases; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.main_deleted_cases TO dev;


--
-- Name: TABLE main_ingestion; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.main_ingestion TO dev;


--
-- Name: TABLE main_section_update; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.main_section_update TO dev;


--
-- Name: TABLE mark_all_for_hc; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.mark_all_for_hc TO dev;


--
-- Name: SEQUENCE mark_all_for_hc_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.mark_all_for_hc_id_seq TO dev;


--
-- Name: TABLE mark_all_for_scrutiny; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.mark_all_for_scrutiny TO dev;


--
-- Name: SEQUENCE mark_all_for_scrutiny_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.mark_all_for_scrutiny_id_seq TO dev;


--
-- Name: TABLE matched_disposal_data; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.matched_disposal_data TO dev;


--
-- Name: TABLE matters_auto_updated; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.matters_auto_updated TO dev;


--
-- Name: TABLE matters_with_wrong_section; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.matters_with_wrong_section TO dev;


--
-- Name: SEQUENCE matters_with_wrong_section_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.matters_with_wrong_section_id_seq TO dev;


--
-- Name: TABLE mention_memo; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.mention_memo TO dev;


--
-- Name: TABLE mention_memo_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.mention_memo_history TO dev;


--
-- Name: SEQUENCE mention_memo_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.mention_memo_id_seq TO dev;


--
-- Name: TABLE mobile_numbers_wa; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.mobile_numbers_wa TO dev;


--
-- Name: SEQUENCE mobile_numbers_wa_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.mobile_numbers_wa_id_seq TO dev;


--
-- Name: TABLE module_entry_session; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.module_entry_session TO dev;


--
-- Name: TABLE msg; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.msg TO dev;


--
-- Name: SEQUENCE msg_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.msg_id_seq TO dev;


--
-- Name: TABLE mul_category; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.mul_category TO dev;


--
-- Name: SEQUENCE mul_category_mul_category_idd_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.mul_category_mul_category_idd_seq TO dev;


--
-- Name: TABLE neutral_citation; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.neutral_citation TO dev;


--
-- Name: TABLE neutral_citation_01072023; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.neutral_citation_01072023 TO dev;


--
-- Name: SEQUENCE neutral_citation_01072023_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.neutral_citation_01072023_id_seq TO dev;


--
-- Name: TABLE neutral_citation_06072023; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.neutral_citation_06072023 TO dev;


--
-- Name: SEQUENCE neutral_citation_06072023_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.neutral_citation_06072023_id_seq TO dev;


--
-- Name: TABLE neutral_citation_24042023; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.neutral_citation_24042023 TO dev;


--
-- Name: SEQUENCE neutral_citation_24042023_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.neutral_citation_24042023_id_seq TO dev;


--
-- Name: TABLE neutral_citation_deleted; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.neutral_citation_deleted TO dev;


--
-- Name: SEQUENCE neutral_citation_deleted_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.neutral_citation_deleted_id_seq TO dev;


--
-- Name: SEQUENCE neutral_citation_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.neutral_citation_id_seq TO dev;


--
-- Name: TABLE new_subject_category_updation; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.new_subject_category_updation TO dev;


--
-- Name: SEQUENCE new_subject_category_updation_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.new_subject_category_updation_id_seq TO dev;


--
-- Name: TABLE nic_cloud_tbfh; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.nic_cloud_tbfh TO dev;


--
-- Name: TABLE njdg_act; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.njdg_act TO dev;


--
-- Name: TABLE njdg_category_transaction; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.njdg_category_transaction TO dev;


--
-- Name: TABLE njdg_cino; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.njdg_cino TO dev;


--
-- Name: SEQUENCE njdg_cino_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.njdg_cino_id_seq TO dev;


--
-- Name: TABLE njdg_lower_court; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.njdg_lower_court TO dev;


--
-- Name: TABLE njdg_ordernet; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.njdg_ordernet TO dev;


--
-- Name: TABLE njdg_ordernet_16102022; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.njdg_ordernet_16102022 TO dev;


--
-- Name: TABLE njdg_purpose; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.njdg_purpose TO dev;


--
-- Name: TABLE njdg_stats; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.njdg_stats TO dev;


--
-- Name: SEQUENCE njdg_stats_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.njdg_stats_id_seq TO dev;


--
-- Name: TABLE njdg_transaction; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.njdg_transaction TO dev;


--
-- Name: TABLE njdg_transaction_bck_11102022; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.njdg_transaction_bck_11102022 TO dev;


--
-- Name: TABLE njrs_mails; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.njrs_mails TO dev;


--
-- Name: SEQUENCE njrs_mails_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.njrs_mails_id_seq TO dev;


--
-- Name: TABLE not_before; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.not_before TO dev;


--
-- Name: TABLE not_before_his; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.not_before_his TO dev;


--
-- Name: SEQUENCE not_before_his_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.not_before_his_id_seq TO dev;


--
-- Name: SEQUENCE not_before_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.not_before_id_seq TO dev;


--
-- Name: TABLE obj_save; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.obj_save TO dev;


--
-- Name: TABLE obj_save_his; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.obj_save_his TO dev;


--
-- Name: SEQUENCE obj_save_his_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.obj_save_his_id_seq TO dev;


--
-- Name: SEQUENCE obj_save_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.obj_save_id_seq TO dev;


--
-- Name: TABLE objrem; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.objrem TO dev;


--
-- Name: TABLE office_report_details; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.office_report_details TO dev;


--
-- Name: SEQUENCE office_report_details_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.office_report_details_id_seq TO dev;


--
-- Name: TABLE or_gist; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.or_gist TO dev;


--
-- Name: SEQUENCE or_gist_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.or_gist_id_seq TO dev;


--
-- Name: TABLE order_type_changed_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.order_type_changed_log TO dev;


--
-- Name: SEQUENCE order_type_changed_log_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.order_type_changed_log_id_seq TO dev;


--
-- Name: TABLE ordernet; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ordernet TO dev;


--
-- Name: TABLE ordernet_deleted; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ordernet_deleted TO dev;


--
-- Name: SEQUENCE ordernet_deleted_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ordernet_deleted_id_seq TO dev;


--
-- Name: SEQUENCE ordernet_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ordernet_id_seq TO dev;


--
-- Name: TABLE ordernet_org; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ordernet_org TO dev;


--
-- Name: SEQUENCE ordernet_org_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.ordernet_org_id_seq TO dev;


--
-- Name: TABLE ordernet_rop_sci; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.ordernet_rop_sci TO dev;


--
-- Name: TABLE original_records_file; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.original_records_file TO dev;


--
-- Name: SEQUENCE original_records_file_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.original_records_file_id_seq TO dev;


--
-- Name: TABLE other_category; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.other_category TO dev;


--
-- Name: SEQUENCE other_category_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.other_category_id_seq TO dev;


--
-- Name: TABLE otp_based_login_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.otp_based_login_history TO dev;


--
-- Name: SEQUENCE otp_based_login_history_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.otp_based_login_history_id_seq TO dev;


--
-- Name: TABLE otp_sent_detail; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.otp_sent_detail TO dev;


--
-- Name: SEQUENCE otp_sent_detail_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.otp_sent_detail_id_seq TO dev;


--
-- Name: TABLE pap_book; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.pap_book TO dev;


--
-- Name: SEQUENCE pap_book_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.pap_book_id_seq TO dev;


--
-- Name: TABLE paper_book_sms_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.paper_book_sms_log TO dev;


--
-- Name: SEQUENCE paper_book_sms_log_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.paper_book_sms_log_id_seq TO dev;


--
-- Name: TABLE party; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.party TO dev;


--
-- Name: TABLE party_additional_address; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.party_additional_address TO dev;


--
-- Name: SEQUENCE party_additional_address_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.party_additional_address_id_seq TO dev;


--
-- Name: SEQUENCE party_auto_generated_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.party_auto_generated_id_seq TO dev;


--
-- Name: TABLE party_autocomp; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.party_autocomp TO dev;


--
-- Name: TABLE party_lowercourt; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.party_lowercourt TO dev;


--
-- Name: SEQUENCE party_lowercourt_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.party_lowercourt_id_seq TO dev;


--
-- Name: TABLE party_order; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.party_order TO dev;


--
-- Name: SEQUENCE party_order_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.party_order_id_seq TO dev;


--
-- Name: TABLE pendency_report; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.pendency_report TO dev;


--
-- Name: SEQUENCE pendency_report_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.pendency_report_id_seq TO dev;


--
-- Name: TABLE pending_cases_section_id; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.pending_cases_section_id TO dev;


--
-- Name: TABLE physical_hearing_advocate_consent; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.physical_hearing_advocate_consent TO dev;


--
-- Name: SEQUENCE physical_hearing_advocate_consent_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.physical_hearing_advocate_consent_id_seq TO dev;


--
-- Name: TABLE physical_hearing_advocate_consent_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.physical_hearing_advocate_consent_log TO dev;


--
-- Name: TABLE physical_hearing_consent_required; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.physical_hearing_consent_required TO dev;


--
-- Name: SEQUENCE physical_hearing_consent_required_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.physical_hearing_consent_required_id_seq TO dev;


--
-- Name: TABLE physical_hearing_consent_required_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.physical_hearing_consent_required_log TO dev;


--
-- Name: SEQUENCE physical_hearing_consent_required_log_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.physical_hearing_consent_required_log_id_seq TO dev;


--
-- Name: TABLE physical_verify; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.physical_verify TO dev;


--
-- Name: TABLE physical_verify_old; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.physical_verify_old TO dev;


--
-- Name: TABLE post_bar_code_mapping; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.post_bar_code_mapping TO dev;


--
-- Name: SEQUENCE post_bar_code_mapping_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.post_bar_code_mapping_id_seq TO dev;


--
-- Name: TABLE post_envelope_movement; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.post_envelope_movement TO dev;


--
-- Name: SEQUENCE post_envelope_movement_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.post_envelope_movement_id_seq TO dev;


--
-- Name: TABLE proceedings; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.proceedings TO dev;


--
-- Name: SEQUENCE proceedings_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.proceedings_id_seq TO dev;


--
-- Name: TABLE recalled_deleted; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.recalled_deleted TO dev;


--
-- Name: TABLE recalled_matters; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.recalled_matters TO dev;


--
-- Name: TABLE recalled_matters_21122018; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.recalled_matters_21122018 TO dev;


--
-- Name: TABLE record_keeping; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.record_keeping TO dev;


--
-- Name: SEQUENCE record_keeping_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.record_keeping_id_seq TO dev;


--
-- Name: TABLE record_room_mails; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.record_room_mails TO dev;


--
-- Name: SEQUENCE record_room_mails_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.record_room_mails_id_seq TO dev;


--
-- Name: TABLE refiled_old_efiling_case_efiled_docs; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.refiled_old_efiling_case_efiled_docs TO dev;


--
-- Name: SEQUENCE refiled_old_efiling_case_efiled_docs_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.refiled_old_efiling_case_efiled_docs_id_seq TO dev;


--
-- Name: TABLE reg_dt0; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.reg_dt0 TO dev;


--
-- Name: TABLE registered_cases; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.registered_cases TO dev;


--
-- Name: SEQUENCE registered_cases_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.registered_cases_id_seq TO dev;


--
-- Name: TABLE registration_track; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.registration_track TO dev;


--
-- Name: SEQUENCE registration_track_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.registration_track_id_seq TO dev;


--
-- Name: TABLE relied_details; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.relied_details TO dev;


--
-- Name: SEQUENCE relied_details_relied_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.relied_details_relied_id_seq TO dev;


--
-- Name: TABLE renewed_caveat; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.renewed_caveat TO dev;


--
-- Name: SEQUENCE renewed_caveat_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.renewed_caveat_id_seq TO dev;


--
-- Name: TABLE requistion_upload; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.requistion_upload TO dev;


--
-- Name: SEQUENCE requistion_upload_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.requistion_upload_id_seq TO dev;


--
-- Name: TABLE restored; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.restored TO dev;


--
-- Name: TABLE rgo_default; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.rgo_default TO dev;


--
-- Name: TABLE rgo_default_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.rgo_default_history TO dev;


--
-- Name: TABLE sc_working_days_23052019; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.sc_working_days_23052019 TO dev;


--
-- Name: SEQUENCE sc_working_days_23052019_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.sc_working_days_23052019_id_seq TO dev;


--
-- Name: TABLE scan_movement; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.scan_movement TO dev;


--
-- Name: TABLE scan_movement_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.scan_movement_history TO dev;


--
-- Name: SEQUENCE scan_movement_history_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.scan_movement_history_id_seq TO dev;


--
-- Name: SEQUENCE scan_movement_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.scan_movement_id_seq TO dev;


--
-- Name: TABLE sclsc_details; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.sclsc_details TO dev;


--
-- Name: SEQUENCE sclsc_details_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.sclsc_details_id_seq TO dev;


--
-- Name: TABLE scordermain; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.scordermain TO dev;


--
-- Name: SEQUENCE scordermain_id_dn_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.scordermain_id_dn_seq TO dev;


--
-- Name: TABLE section_id_change; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.section_id_change TO dev;


--
-- Name: SEQUENCE section_id_change_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.section_id_change_id_seq TO dev;


--
-- Name: TABLE sensitive_cases; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.sensitive_cases TO dev;


--
-- Name: SEQUENCE sensitive_cases_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.sensitive_cases_id_seq TO dev;


--
-- Name: TABLE sentence_period; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.sentence_period TO dev;


--
-- Name: SEQUENCE sentence_period_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.sentence_period_id_seq TO dev;


--
-- Name: TABLE sentence_undergone; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.sentence_undergone TO dev;


--
-- Name: SEQUENCE sentence_undergone_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.sentence_undergone_id_seq TO dev;


--
-- Name: TABLE showlcd; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.showlcd TO dev;


--
-- Name: TABLE showlcd_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.showlcd_history TO dev;


--
-- Name: TABLE sign_document; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.sign_document TO dev;


--
-- Name: SEQUENCE sign_document_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.sign_document_id_seq TO dev;


--
-- Name: TABLE similarity_details; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.similarity_details TO dev;


--
-- Name: TABLE similarity_details_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.similarity_details_history TO dev;


--
-- Name: SEQUENCE similarity_details_history_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.similarity_details_history_id_seq TO dev;


--
-- Name: SEQUENCE similarity_details_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.similarity_details_id_seq TO dev;


--
-- Name: TABLE single_judge_advance_cl_printed; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.single_judge_advance_cl_printed TO dev;


--
-- Name: SEQUENCE single_judge_advance_cl_printed_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.single_judge_advance_cl_printed_id_seq TO dev;


--
-- Name: TABLE single_judge_advanced_drop_note; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.single_judge_advanced_drop_note TO dev;


--
-- Name: SEQUENCE single_judge_advanced_drop_note_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.single_judge_advanced_drop_note_id_seq TO dev;


--
-- Name: TABLE sms_drop_cl; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.sms_drop_cl TO dev;


--
-- Name: TABLE sms_hc_cl; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.sms_hc_cl TO dev;


--
-- Name: TABLE sms_hc_cl_17042023; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.sms_hc_cl_17042023 TO dev;


--
-- Name: TABLE sms_pool; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.sms_pool TO dev;


--
-- Name: SEQUENCE sms_pool_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.sms_pool_id_seq TO dev;


--
-- Name: TABLE sms_weekly; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.sms_weekly TO dev;


--
-- Name: SEQUENCE sms_weekly_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.sms_weekly_id_seq TO dev;


--
-- Name: TABLE special_category_filing; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.special_category_filing TO dev;


--
-- Name: SEQUENCE special_category_filing_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.special_category_filing_id_seq TO dev;


--
-- Name: TABLE submaster_old; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.submaster_old TO dev;


--
-- Name: TABLE tbl_court_requisition; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tbl_court_requisition TO dev;


--
-- Name: SEQUENCE tbl_court_requisition_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.tbl_court_requisition_id_seq TO dev;


--
-- Name: TABLE tbl_library_section; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tbl_library_section TO dev;


--
-- Name: TABLE tbl_requisition_department; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tbl_requisition_department TO dev;


--
-- Name: TABLE tbl_requisition_interactions; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tbl_requisition_interactions TO dev;


--
-- Name: TABLE tbl_requisition_request; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tbl_requisition_request TO dev;


--
-- Name: TABLE temp; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.temp TO dev;


--
-- Name: TABLE temp_sclsc_cvs; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.temp_sclsc_cvs TO dev;


--
-- Name: TABLE temp_table; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.temp_table TO dev;


--
-- Name: TABLE tempo; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tempo TO dev;


--
-- Name: TABLE tempo_deleted; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tempo_deleted TO dev;


--
-- Name: SEQUENCE tempo_deleted_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.tempo_deleted_id_seq TO dev;


--
-- Name: SEQUENCE tempo_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.tempo_id_seq TO dev;


--
-- Name: TABLE transactions; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.transactions TO dev;


--
-- Name: SEQUENCE transactions_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.transactions_id_seq TO dev;


--
-- Name: TABLE transcribed_arguments; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.transcribed_arguments TO dev;


--
-- Name: SEQUENCE transcribed_arguments_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.transcribed_arguments_id_seq TO dev;


--
-- Name: TABLE transfer_old_com_gen_cases; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.transfer_old_com_gen_cases TO dev;


--
-- Name: TABLE transfer_to_details; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.transfer_to_details TO dev;


--
-- Name: SEQUENCE transfer_to_details_transfer_to_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.transfer_to_details_transfer_to_id_seq TO dev;


--
-- Name: TABLE tw_comp_not; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tw_comp_not TO dev;


--
-- Name: TABLE tw_comp_not_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tw_comp_not_history TO dev;


--
-- Name: SEQUENCE tw_comp_not_history_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.tw_comp_not_history_id_seq TO dev;


--
-- Name: SEQUENCE tw_comp_not_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.tw_comp_not_id_seq TO dev;


--
-- Name: TABLE tw_not_pen_sta; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tw_not_pen_sta TO dev;


--
-- Name: SEQUENCE tw_not_pen_sta_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.tw_not_pen_sta_id_seq TO dev;


--
-- Name: TABLE tw_o_r; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tw_o_r TO dev;


--
-- Name: SEQUENCE tw_o_r_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.tw_o_r_id_seq TO dev;


--
-- Name: TABLE tw_pro_desc; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tw_pro_desc TO dev;


--
-- Name: SEQUENCE tw_pro_desc_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.tw_pro_desc_id_seq TO dev;


--
-- Name: TABLE tw_tal_del; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tw_tal_del TO dev;


--
-- Name: SEQUENCE tw_tal_del_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.tw_tal_del_id_seq TO dev;


--
-- Name: TABLE update_heardt_reason; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.update_heardt_reason TO dev;


--
-- Name: SEQUENCE update_heardt_reason_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.update_heardt_reason_id_seq TO dev;


--
-- Name: TABLE users_22092000; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.users_22092000 TO dev;


--
-- Name: SEQUENCE users_22092000_usercode_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.users_22092000_usercode_seq TO dev;


--
-- Name: TABLE users_dump; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.users_dump TO dev;


--
-- Name: SEQUENCE users_dump_usercode_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.users_dump_usercode_seq TO dev;


--
-- Name: TABLE vacation_advance_list; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacation_advance_list TO dev;


--
-- Name: TABLE vacation_advance_list_advocate; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacation_advance_list_advocate TO dev;


--
-- Name: TABLE vacation_advance_list_advocate_2018; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacation_advance_list_advocate_2018 TO dev;


--
-- Name: SEQUENCE vacation_advance_list_advocate_2018_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.vacation_advance_list_advocate_2018_id_seq TO dev;


--
-- Name: TABLE vacation_advance_list_advocate_2023_backup; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacation_advance_list_advocate_2023_backup TO dev;


--
-- Name: SEQUENCE vacation_advance_list_advocate_2023_backup_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.vacation_advance_list_advocate_2023_backup_id_seq TO dev;


--
-- Name: TABLE vacation_advance_list_advocate_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacation_advance_list_advocate_log TO dev;


--
-- Name: TABLE vacation_advance_list_advocate_log_old; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacation_advance_list_advocate_log_old TO dev;


--
-- Name: TABLE vacation_advance_list_advocate_old; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacation_advance_list_advocate_old TO dev;


--
-- Name: SEQUENCE vacation_advance_list_advocate_old_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.vacation_advance_list_advocate_old_id_seq TO dev;


--
-- Name: TABLE vacation_advance_list_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacation_advance_list_log TO dev;


--
-- Name: TABLE vacation_advance_list_log_old; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacation_advance_list_log_old TO dev;


--
-- Name: TABLE vacation_advance_list_old; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacation_advance_list_old TO dev;


--
-- Name: SEQUENCE vacation_advance_list_old_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.vacation_advance_list_old_id_seq TO dev;


--
-- Name: TABLE vacation_list_data; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacation_list_data TO dev;


--
-- Name: TABLE vacation_registrar_not_ready_cl; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacation_registrar_not_ready_cl TO dev;


--
-- Name: TABLE vacation_registrar_pool; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vacation_registrar_pool TO dev;


--
-- Name: TABLE vc_room_details; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vc_room_details TO dev;


--
-- Name: SEQUENCE vc_room_details_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.vc_room_details_id_seq TO dev;


--
-- Name: TABLE vc_stats; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vc_stats TO dev;


--
-- Name: SEQUENCE vc_stats_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.vc_stats_id_seq TO dev;


--
-- Name: TABLE vc_webcast_details; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vc_webcast_details TO dev;


--
-- Name: SEQUENCE vc_webcast_details_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.vc_webcast_details_id_seq TO dev;


--
-- Name: TABLE vc_webcast_details_temp; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vc_webcast_details_temp TO dev;


--
-- Name: SEQUENCE vc_webcast_details_temp_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.vc_webcast_details_temp_id_seq TO dev;


--
-- Name: TABLE vc_webcast_history; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vc_webcast_history TO dev;


--
-- Name: SEQUENCE vc_webcast_history_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.vc_webcast_history_id_seq TO dev;


--
-- Name: TABLE verify_digital_signature; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.verify_digital_signature TO dev;


--
-- Name: TABLE verify_hcor; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.verify_hcor TO dev;


--
-- Name: TABLE vernacular_orders_judgments; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.vernacular_orders_judgments TO dev;


--
-- Name: SEQUENCE vernacular_orders_judgments_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.vernacular_orders_judgments_id_seq TO dev;


--
-- Name: TABLE virtual_justice_clock; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.virtual_justice_clock TO dev;


--
-- Name: TABLE virtual_justice_clock_casetype; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.virtual_justice_clock_casetype TO dev;


--
-- Name: SEQUENCE virtual_justice_clock_casetype_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.virtual_justice_clock_casetype_id_seq TO dev;


--
-- Name: SEQUENCE virtual_justice_clock_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.virtual_justice_clock_id_seq TO dev;


--
-- Name: TABLE virtual_justice_clock_main_subject_category; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.virtual_justice_clock_main_subject_category TO dev;


--
-- Name: SEQUENCE virtual_justice_clock_main_subject_category_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.virtual_justice_clock_main_subject_category_id_seq TO dev;


--
-- Name: TABLE virtual_justice_clock_scrutiny; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.virtual_justice_clock_scrutiny TO dev;


--
-- Name: SEQUENCE virtual_justice_clock_scrutiny_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.virtual_justice_clock_scrutiny_id_seq TO dev;


--
-- Name: TABLE weekly_list; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.weekly_list TO dev;


--
-- Name: SEQUENCE weekly_list_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.weekly_list_id_seq TO dev;


--
-- Name: TABLE whatsapp_pool; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.whatsapp_pool TO dev;


--
-- Name: SEQUENCE whatsapp_pool_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.whatsapp_pool_id_seq TO dev;


--
-- PostgreSQL database dump complete
--

