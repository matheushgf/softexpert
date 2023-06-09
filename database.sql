PGDMP                         {         
   softexpert    15.2    15.2                0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false                       0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false                       0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false                       1262    24595 
   softexpert    DATABASE     �   CREATE DATABASE softexpert WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'Portuguese_Brazil.1252';
    DROP DATABASE softexpert;
                postgres    false            �            1259    24596 
   categorias    TABLE     �   CREATE TABLE public.categorias (
    id integer NOT NULL,
    nome character varying(30) NOT NULL,
    imposto real NOT NULL,
    status boolean DEFAULT true NOT NULL
);
    DROP TABLE public.categorias;
       public         heap    postgres    false            �            1259    24600    categorias_id_seq    SEQUENCE     �   CREATE SEQUENCE public.categorias_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.categorias_id_seq;
       public          postgres    false    214            	           0    0    categorias_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.categorias_id_seq OWNED BY public.categorias.id;
          public          postgres    false    215            �            1259    24601    produto_categorias    TABLE     �   CREATE TABLE public.produto_categorias (
    id integer NOT NULL,
    produto_id integer NOT NULL,
    categoria_id integer NOT NULL,
    status boolean DEFAULT true NOT NULL
);
 &   DROP TABLE public.produto_categorias;
       public         heap    postgres    false            �            1259    24605    produto_categorias_id_seq    SEQUENCE     �   CREATE SEQUENCE public.produto_categorias_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE public.produto_categorias_id_seq;
       public          postgres    false    216            
           0    0    produto_categorias_id_seq    SEQUENCE OWNED BY     W   ALTER SEQUENCE public.produto_categorias_id_seq OWNED BY public.produto_categorias.id;
          public          postgres    false    217            �            1259    24606    produtos    TABLE     �   CREATE TABLE public.produtos (
    id integer NOT NULL,
    nome character varying(30) NOT NULL,
    preco real NOT NULL,
    status boolean DEFAULT true NOT NULL
);
    DROP TABLE public.produtos;
       public         heap    postgres    false            �            1259    24610    produtos_id_seq    SEQUENCE     �   CREATE SEQUENCE public.produtos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.produtos_id_seq;
       public          postgres    false    218                       0    0    produtos_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.produtos_id_seq OWNED BY public.produtos.id;
          public          postgres    false    219            o           2604    24611    categorias id    DEFAULT     n   ALTER TABLE ONLY public.categorias ALTER COLUMN id SET DEFAULT nextval('public.categorias_id_seq'::regclass);
 <   ALTER TABLE public.categorias ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    215    214            q           2604    24612    produto_categorias id    DEFAULT     ~   ALTER TABLE ONLY public.produto_categorias ALTER COLUMN id SET DEFAULT nextval('public.produto_categorias_id_seq'::regclass);
 D   ALTER TABLE public.produto_categorias ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    217    216            s           2604    24613    produtos id    DEFAULT     j   ALTER TABLE ONLY public.produtos ALTER COLUMN id SET DEFAULT nextval('public.produtos_id_seq'::regclass);
 :   ALTER TABLE public.produtos ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    219    218           