--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.6
-- Dumped by pg_dump version 10.4

-- Started on 2018-08-19 08:52:05

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 3 (class 2615 OID 2200)
-- Name: voucherpool; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA voucherpool;


ALTER SCHEMA voucherpool OWNER TO postgres;

--
-- TOC entry 2156 (class 0 OID 0)
-- Dependencies: 3
-- Name: SCHEMA voucherpool; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA voucherpool IS 'standard public schema';


--
-- TOC entry 1 (class 3079 OID 12387)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2157 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 188 (class 1259 OID 24590)
-- Name: customer; Type: TABLE; Schema: voucherpool; Owner: postgres
--

CREATE TABLE voucherpool.customer (
    customer_id bigint NOT NULL,
    name character varying,
    email character varying NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE voucherpool.customer OWNER TO postgres;

--
-- TOC entry 187 (class 1259 OID 24588)
-- Name: customer_customer_id_seq; Type: SEQUENCE; Schema: voucherpool; Owner: postgres
--

CREATE SEQUENCE voucherpool.customer_customer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE voucherpool.customer_customer_id_seq OWNER TO postgres;

--
-- TOC entry 2158 (class 0 OID 0)
-- Dependencies: 187
-- Name: customer_customer_id_seq; Type: SEQUENCE OWNED BY; Schema: voucherpool; Owner: postgres
--

ALTER SEQUENCE voucherpool.customer_customer_id_seq OWNED BY voucherpool.customer.customer_id;


--
-- TOC entry 190 (class 1259 OID 40991)
-- Name: offer; Type: TABLE; Schema: voucherpool; Owner: postgres
--

CREATE TABLE voucherpool.offer (
    offer_id bigint NOT NULL,
    name character varying NOT NULL,
    discount real,
    expires_at timestamp with time zone
);


ALTER TABLE voucherpool.offer OWNER TO postgres;

--
-- TOC entry 189 (class 1259 OID 40989)
-- Name: offer_offer_id_seq; Type: SEQUENCE; Schema: voucherpool; Owner: postgres
--

CREATE SEQUENCE voucherpool.offer_offer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE voucherpool.offer_offer_id_seq OWNER TO postgres;

--
-- TOC entry 2159 (class 0 OID 0)
-- Dependencies: 189
-- Name: offer_offer_id_seq; Type: SEQUENCE OWNED BY; Schema: voucherpool; Owner: postgres
--

ALTER SEQUENCE voucherpool.offer_offer_id_seq OWNED BY voucherpool.offer.offer_id;


--
-- TOC entry 186 (class 1259 OID 24579)
-- Name: voucher; Type: TABLE; Schema: voucherpool; Owner: postgres
--

CREATE TABLE voucherpool.voucher (
    voucher_id bigint NOT NULL,
    expires_at timestamp without time zone NOT NULL,
    code character varying,
    used_at timestamp with time zone,
    offer_id bigint,
    customer_id bigint
);


ALTER TABLE voucherpool.voucher OWNER TO postgres;

--
-- TOC entry 185 (class 1259 OID 24577)
-- Name: voucher_voucher_id_seq; Type: SEQUENCE; Schema: voucherpool; Owner: postgres
--

CREATE SEQUENCE voucherpool.voucher_voucher_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE voucherpool.voucher_voucher_id_seq OWNER TO postgres;

--
-- TOC entry 2160 (class 0 OID 0)
-- Dependencies: 185
-- Name: voucher_voucher_id_seq; Type: SEQUENCE OWNED BY; Schema: voucherpool; Owner: postgres
--

ALTER SEQUENCE voucherpool.voucher_voucher_id_seq OWNED BY voucherpool.voucher.voucher_id;


--
-- TOC entry 2017 (class 2604 OID 24593)
-- Name: customer customer_id; Type: DEFAULT; Schema: voucherpool; Owner: postgres
--

ALTER TABLE ONLY voucherpool.customer ALTER COLUMN customer_id SET DEFAULT nextval('voucherpool.customer_customer_id_seq'::regclass);


--
-- TOC entry 2018 (class 2604 OID 40994)
-- Name: offer offer_id; Type: DEFAULT; Schema: voucherpool; Owner: postgres
--

ALTER TABLE ONLY voucherpool.offer ALTER COLUMN offer_id SET DEFAULT nextval('voucherpool.offer_offer_id_seq'::regclass);


--
-- TOC entry 2016 (class 2604 OID 24582)
-- Name: voucher voucher_id; Type: DEFAULT; Schema: voucherpool; Owner: postgres
--

ALTER TABLE ONLY voucherpool.voucher ALTER COLUMN voucher_id SET DEFAULT nextval('voucherpool.voucher_voucher_id_seq'::regclass);


--
-- TOC entry 2026 (class 2606 OID 32789)
-- Name: customer cemail_unique; Type: CONSTRAINT; Schema: voucherpool; Owner: postgres
--

ALTER TABLE ONLY voucherpool.customer
    ADD CONSTRAINT cemail_unique UNIQUE (email);


--
-- TOC entry 2028 (class 2606 OID 24598)
-- Name: customer customer_pkey; Type: CONSTRAINT; Schema: voucherpool; Owner: postgres
--

ALTER TABLE ONLY voucherpool.customer
    ADD CONSTRAINT customer_pkey PRIMARY KEY (customer_id);


--
-- TOC entry 2030 (class 2606 OID 40999)
-- Name: offer offer_pkey; Type: CONSTRAINT; Schema: voucherpool; Owner: postgres
--

ALTER TABLE ONLY voucherpool.offer
    ADD CONSTRAINT offer_pkey PRIMARY KEY (offer_id);


--
-- TOC entry 2022 (class 2606 OID 32787)
-- Name: voucher vcode_unique; Type: CONSTRAINT; Schema: voucherpool; Owner: postgres
--

ALTER TABLE ONLY voucherpool.voucher
    ADD CONSTRAINT vcode_unique UNIQUE (code);


--
-- TOC entry 2024 (class 2606 OID 24587)
-- Name: voucher voucher_pkey; Type: CONSTRAINT; Schema: voucherpool; Owner: postgres
--

ALTER TABLE ONLY voucherpool.voucher
    ADD CONSTRAINT voucher_pkey PRIMARY KEY (voucher_id);


--
-- TOC entry 2019 (class 1259 OID 41005)
-- Name: fki_fkcustomerid; Type: INDEX; Schema: voucherpool; Owner: postgres
--

CREATE INDEX fki_fkcustomerid ON voucherpool.voucher USING btree (customer_id);


--
-- TOC entry 2020 (class 1259 OID 41021)
-- Name: fki_fkofferid; Type: INDEX; Schema: voucherpool; Owner: postgres
--

CREATE INDEX fki_fkofferid ON voucherpool.voucher USING btree (offer_id);


--
-- TOC entry 2031 (class 2606 OID 41000)
-- Name: voucher fkcustomerid; Type: FK CONSTRAINT; Schema: voucherpool; Owner: postgres
--

ALTER TABLE ONLY voucherpool.voucher
    ADD CONSTRAINT fkcustomerid FOREIGN KEY (customer_id) REFERENCES voucherpool.customer(customer_id);


--
-- TOC entry 2032 (class 2606 OID 41016)
-- Name: voucher fkofferid; Type: FK CONSTRAINT; Schema: voucherpool; Owner: postgres
--

ALTER TABLE ONLY voucherpool.voucher
    ADD CONSTRAINT fkofferid FOREIGN KEY (offer_id) REFERENCES voucherpool.offer(offer_id);


-- Completed on 2018-08-19 08:52:07

--
-- PostgreSQL database dump complete
--

