--
-- PostgreSQL database cluster dump
--

SET default_transaction_read_only = off;

SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;

--
-- Databases
--

--
-- Database "template1" dump
--

\connect template1

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.1
-- Dumped by pg_dump version 16.1

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
-- PostgreSQL database dump complete
--

--
-- Database "postgres" dump
--

\connect postgres

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.1
-- Dumped by pg_dump version 16.1

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
-- PostgreSQL database dump complete
--

--
-- Database "symfony-api" dump
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.1
-- Dumped by pg_dump version 16.1

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
-- Name: symfony-api; Type: DATABASE; Schema: -; Owner: symfony
--

ALTER DATABASE "symfony-api" OWNER TO symfony;

\connect -reuse-previous=on "dbname='symfony-api'"

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
-- Data for Name: api_client; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public.api_client (id, name, access_key) FROM stdin;
1	main	2e2df55176160cabaa35729df3f32c85
2	doc	3dbe06033befda5cc41c5fa2197efcb6
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
DoctrineMigrations\\Version20240210114457	2024-02-10 11:45:04	60
DoctrineMigrations\\Version20240211044930	2024-02-11 04:49:35	50
\.


--
-- Data for Name: geo_city; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public.geo_city (id, code, title, latitude, longitude, timezone, country_code) FROM stdin;
1	R01	Moscow	55.75322	37.622513	3	643
2	R02	Saint-Petersburg	59.939099	30.315877	3	643
3	R03	Yekaterinburg	56.838011	60.597474	5	643
4	R04	Kazan	55.796127	49.106414	3	643
5	R05	Novosibirsk	55.030204	82.92043	7	643
6	R06	Vladivostok	43.115542	131.885494	10	643
7	K01	Astana	51.128207	71.43042	6	398
8	K02	Almaty	43.237163	76.945627	3	398
9	K03	Shymkent	42.315514	69.586916	6	398
10	A01	Erevan	40.177628	44.512555	4	051
11	A02	Gyumri	40.785273	43.841774	4	051
12	A03	Vanadzor	40.811432	44.485283	4	051
13	G01	Berlin	52.5244	13.4105	1	276
14	G02	Gamburg	53.5753	10.0153	1	276
15	G03	Munchen	48.1374	11.5755	1	276
16	U01	Kiev	50.4547	30.5238	2	804
17	U02	Odessa	46.4775	30.7326	2	804
18	U03	Kharkov	49.9808	36.2527	2	804
19	S01	Madrid	40.4165	-3.70256	1	978
20	S02	Barcelona	41.3888	2.15899	1	978
21	S03	Valencia	39.4697	-0.37739	1	978
22	N01	Amsterdam	52.374	4.88969	1	528
23	N02	Rotterdam	51.9225	4.47917	1	528
24	N03	Den Haag	52.0767	4.29861	1	528
25	M01	Podgorica	42.4411	19.2636	1	499
26	M02	Bar	42.099998	19.1	1	499
27	M03	Budva	42.291149	18.840295	1	499
28	C01	Limassol	34.6841	33.0379	2	196
29	C02	Paphos	34.77679	32.42451	2	196
30	C03	Larnaca	34.9229	33.6233	2	196
31	G01	Tbilisi	41.6941	44.8337	4	268
32	G02	Batumi	41.6423	41.6339	4	268
33	G03	Borjomi	41.8399528	43.3907569	4	268
34	I01	Rome	41.8919	12.5113	1	380
35	I02	Milan	45.4643	9.18951	1	380
36	I03	Naples	40.8522	14.2681	1	380
\.


--
-- Data for Name: geo_country; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public.geo_country (id, title, code_iso, phone_mask, phone_length, sort_priority, code_alpha) FROM stdin;
3	Armenia	051	374	11	3	ARM
1	Russia	643	7	11	1	RUS
2	Kazakhstan	398	7	11	2	KAZ
4	Germany	276	49	13	4	DEU
5	Ukraine	804	380	12	5	UKR
6	Spain	978	34	9	6	ESP
7	Netherlands	528	31	9	7	NLD
8	Montenegro	499	382	6	8	MNE
10	Georgia	268	995	9	10	GEO
11	Italy	380	39	11	11	ITA
9	Cyprus	196	357	8	9	CYP
\.


--
-- Data for Name: refresh_token; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public.refresh_token (id, refresh_token, username, valid) FROM stdin;
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public."user" (id, username, name, email, password) FROM stdin;
1	79000000082	user_1	user_1@mail.com	$2y$13$INuQwTmxAJmG8IUSedHJo.5GUL3RbRpgrSB2wI1PRTNpoSVW1eY66
\.


--
-- Name: api_client_id_seq; Type: SEQUENCE SET; Schema: public; Owner: symfony
--

SELECT pg_catalog.setval('public.api_client_id_seq', 1, false);


--
-- Name: geo_city_id_seq; Type: SEQUENCE SET; Schema: public; Owner: symfony
--

SELECT pg_catalog.setval('public.geo_city_id_seq', 1, false);


--
-- Name: geo_country_id_seq; Type: SEQUENCE SET; Schema: public; Owner: symfony
--

SELECT pg_catalog.setval('public.geo_country_id_seq', 1, false);


--
-- Name: refresh_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: symfony
--

SELECT pg_catalog.setval('public.refresh_tokens_id_seq', 20, true);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: symfony
--

SELECT pg_catalog.setval('public.user_id_seq', 1, false);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database cluster dump complete
--

