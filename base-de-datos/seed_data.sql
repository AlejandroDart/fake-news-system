--
-- PostgreSQL database dump
--

\restrict GACF2hY0ohCHUWCf76z3dWMEzFPSK9uEazeQkJDZLErrRyqJILNrTsHzglDqDUz

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

--
-- Data for Name: prediction_history; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.prediction_history VALUES (30, 'Canada and Mexico sign new trade coordination agreement', 'OTTAWA - Canada and Mexico signed a new agreement on Wednesday to strengthen trade coordination and streamline customs procedures across key manufacturing sectors, officials said. The agreement focuses on automotive supply chains, agricultural exports, and technology services. Government representatives said the measure is expected to improve cross-border competitiveness and reduce logistical delays over the coming year.', 'Noticia Real', 0, 0.003417, 0.996583, 'BiLSTM', '{"metodo":"SHAP Text Explainer","interpretacion":{"valor_positivo":"La palabra aumenta la probabilidad de Noticia Falsa.","valor_negativo":"La palabra reduce la probabilidad de Noticia Falsa y empuja hacia Noticia Real.","valor_cercano_a_cero":"La palabra tiene poco impacto en la predicción."},"detalle":[{"palabra":"wednesday","importancia":-0.086206,"direccion":"Empuja hacia Noticia Real"},{"palabra":"strengthen","importancia":-0.050734,"direccion":"Empuja hacia Noticia Real"},{"palabra":"trade","importancia":-0.050734,"direccion":"Empuja hacia Noticia Real"},{"palabra":"agreement","importancia":-0.047182,"direccion":"Empuja hacia Noticia Real"},{"palabra":"coordination","importancia":-0.03293,"direccion":"Empuja hacia Noticia Real"},{"palabra":"new","importancia":-0.031993,"direccion":"Empuja hacia Noticia Real"},{"palabra":"mexico","importancia":-0.031759,"direccion":"Empuja hacia Noticia Real"},{"palabra":"signed","importancia":-0.031759,"direccion":"Empuja hacia Noticia Real"},{"palabra":"ottawa","importancia":-0.029995,"direccion":"Empuja hacia Noticia Real"},{"palabra":"canada","importancia":-0.029995,"direccion":"Empuja hacia Noticia Real"}],"palabras":["wednesday","strengthen","trade","agreement","coordination","new","mexico","signed","ottawa","canada"],"valores":[-0.086206,-0.050734,-0.050734,-0.047182,-0.03293,-0.031993,-0.031759,-0.031759,-0.029995,-0.029995]}', '2026-05-02 23:45:11');
INSERT INTO public.prediction_history VALUES (29, 'BREAKING: Congress approves law allowing social media monitoring of all private messages', 'A bombshell report claims Congress has secretly approved a law that allows federal agencies to monitor all private social media messages without a warrant. Anonymous insiders say the system will begin operating nationwide next week under a new national security protocol. Critics argue the move represents the most extreme expansion of digital surveillance in modern history.', 'Noticia Falsa', 1, 0.999546, 0.000454, 'BiLSTM', '{"metodo":"Análisis de influencia de palabras (método SHAP)","interpretacion":{"valor_positivo":"La palabra aumenta la probabilidad de Noticia Falsa.","valor_negativo":"La palabra reduce la probabilidad de Noticia Falsa y empuja hacia Noticia Real.","valor_cercano_a_cero":"La palabra tiene poco impacto en la predicción."},"detalle":[{"palabra":"next","importancia":-0.000438,"direccion":"Empuja hacia Noticia Real"},{"palabra":"begin","importancia":0.000358,"direccion":"Empuja hacia Noticia Falsa"},{"palabra":"operating","importancia":0.000358,"direccion":"Empuja hacia Noticia Falsa"},{"palabra":"nationwide","importancia":-0.000266,"direccion":"Empuja hacia Noticia Real"},{"palabra":"system","importancia":0.000218,"direccion":"Empuja hacia Noticia Falsa"},{"palabra":"will","importancia":0.000218,"direccion":"Empuja hacia Noticia Falsa"},{"palabra":"messages","importancia":-0.000171,"direccion":"Empuja hacia Noticia Real"},{"palabra":"without","importancia":-0.000171,"direccion":"Empuja hacia Noticia Real"},{"palabra":"report","importancia":0.000124,"direccion":"Empuja hacia Noticia Falsa"},{"palabra":"claims","importancia":0.000124,"direccion":"Empuja hacia Noticia Falsa"}],"palabras":["next","begin","operating","nationwide","system","will","messages","without","report","claims"],"valores":[-0.000438,0.000358,0.000358,-0.000266,0.000218,0.000218,-0.000171,-0.000171,0.000124,0.000124]}', '2026-05-03 01:05:12');


--
-- Name: prediction_history_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.prediction_history_id_seq', 40, true);


--
-- PostgreSQL database dump complete
--

\unrestrict GACF2hY0ohCHUWCf76z3dWMEzFPSK9uEazeQkJDZLErrRyqJILNrTsHzglDqDUz

