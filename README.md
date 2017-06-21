# Colorthing

## Setup

As your main admin user:

  Create the user who will own the database (also, PHP connection):
    
    CREATE USER coloruser WITH PASSWORD 'color';

  Create the database:

    CREATE DATABASE colordb_dev OWNER coloruser;

Log in as the new user in either PSequel or pgAdmin:

  Create colors table:

    CREATE TABLE colors(
      id serial PRIMARY KEY,
      color_name VARCHAR (50) UNIQUE NOT NULL,
      hex_code CHAR (6) NOT NULL
    );

  Create the palettes table:

    CREATE TABLE palettes(
      id serial PRIMARY KEY,
      palette_name VARCHAR (50) UNIQUE NOT NULL
    );

  Create the color_palette table:

    CREATE TABLE color_palette(
      id serial PRIMARY KEY,
      color_id integer NOT NULL,
      palette_id integer NOT NULL
    );

Seed the colors table:

  INSERT INTO colors (color_name, hex_code) VALUES ('Black', '000000');
  INSERT INTO colors (color_name, hex_code) VALUES ('White', 'ffffff');
  INSERT INTO colors (color_name, hex_code) VALUES ('Silver', 'c0c0c0');
  INSERT INTO colors (color_name, hex_code) VALUES ('Gray', '808080');
  INSERT INTO colors (color_name, hex_code) VALUES ('Red', 'ff0000');
  INSERT INTO colors (color_name, hex_code) VALUES ('Orange', 'ffa500');
  INSERT INTO colors (color_name, hex_code) VALUES ('Yellow', 'ffff00');
  INSERT INTO colors (color_name, hex_code) VALUES ('Green', '008000');
  INSERT INTO colors (color_name, hex_code) VALUES ('Blue', '0000ff');
  INSERT INTO colors (color_name, hex_code) VALUES ('Purple', '800080');
