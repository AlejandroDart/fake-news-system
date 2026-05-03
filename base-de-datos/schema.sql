--
-- PostgreSQL database dump
--

\restrict 3BVXDkFSUahDgUQjHETmAYszT2wLfNym84QAEbyOgXDipyOjCLLJwKlFJdSlUPd

-- Dumped from database version 18.3
-- Dumped by pg_dump version 18.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: prediction_history; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.prediction_history (
    id integer NOT NULL,
    titulo text,
    texto text NOT NULL,
    prediccion character varying(50) NOT NULL,
    clase integer NOT NULL,
    probabilidad_fake numeric(10,6),
    probabilidad_real numeric(10,6),
    modelo character varying(100),
    explicacion text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.prediction_history OWNER TO postgres;

--
-- Name: prediction_history_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.prediction_history_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.prediction_history_id_seq OWNER TO postgres;

--
-- Name: prediction_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.prediction_history_id_seq OWNED BY public.prediction_history.id;


--
-- Name: prediction_history id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.prediction_history ALTER COLUMN id SET DEFAULT nextval('public.prediction_history_id_seq'::regclass);


--
-- Name: prediction_history prediction_history_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.prediction_history
    ADD CONSTRAINT prediction_history_pkey PRIMARY KEY (id);


--
-- PostgreSQL database dump complete
--

\unrestrict 3BVXDkFSUahDgUQjHETmAYszT2wLfNym84QAEbyOgXDipyOjCLLJwKlFJdSlUPd

