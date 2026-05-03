--
-- PostgreSQL database dump
--

\restrict tr2CsrJQMAVo5E1gagKe0bSHSp7GapCmeD5Lwy3ncXk9ZdFme9j8WwYmsA4MjdK

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
INSERT INTO public.prediction_history VALUES (36, 'Germany launches new renewable energy investment program', 'BERLIN - Germany announced on Tuesday a new public-private investment
program aimed at expanding renewable energy infrastructure and modernizing
electricity distribution networks.

Officials said the initiative will focus on wind farms, solar energy storage
systems and regional grid resilience projects.

The first phase of funding is expected to begin later this year, with analysts
saying the measure could strengthen long-term energy security and reduce
industrial costs.', 'Noticia Real', 0, 0.003563, 0.996437, 'BiLSTM', '{"metodo":"Análisis de influencia de palabras (método SHAP)","interpretacion":{"valor_positivo":"La palabra aumenta la probabilidad de Noticia Falsa.","valor_negativo":"La palabra reduce la probabilidad de Noticia Falsa y empuja hacia Noticia Real.","valor_cercano_a_cero":"La palabra tiene poco impacto en la predicción."},"detalle":[{"palabra":"program","importancia":-0.08447,"direccion":"Empuja hacia Noticia Real"},{"palabra":"berlin","importancia":-0.08447,"direccion":"Empuja hacia Noticia Real"},{"palabra":"tuesday","importancia":-0.079672,"direccion":"Empuja hacia Noticia Real"},{"palabra":"germany","importancia":-0.072531,"direccion":"Empuja hacia Noticia Real"},{"palabra":"launches","importancia":-0.072531,"direccion":"Empuja hacia Noticia Real"},{"palabra":"new","importancia":-0.065738,"direccion":"Empuja hacia Noticia Real"},{"palabra":"renewable","importancia":-0.065738,"direccion":"Empuja hacia Noticia Real"},{"palabra":"energy","importancia":-0.055062,"direccion":"Empuja hacia Noticia Real"},{"palabra":"investment","importancia":-0.055062,"direccion":"Empuja hacia Noticia Real"},{"palabra":"announced","importancia":-0.023802,"direccion":"Empuja hacia Noticia Real"}],"palabras":["program","berlin","tuesday","germany","launches","new","renewable","energy","investment","announced"],"valores":[-0.08447,-0.08447,-0.079672,-0.072531,-0.072531,-0.065738,-0.065738,-0.055062,-0.055062,-0.023802]}', '2026-05-03 01:02:54');


--
-- Name: prediction_history_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.prediction_history_id_seq', 39, true);


--
-- PostgreSQL database dump complete
--

\unrestrict tr2CsrJQMAVo5E1gagKe0bSHSp7GapCmeD5Lwy3ncXk9ZdFme9j8WwYmsA4MjdK

