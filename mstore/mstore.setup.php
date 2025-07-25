<?php
/* ====================
[BEGIN_COT_EXT]
Name=Mstore
Description=Store Items and Categories
Version=1.0.0
Date=2025-07-21
Author=
Copyright=(c) 
Notes=BSD License
Auth_guests=R
Lock_guests=A
Auth_members=RW1
Lock_members=
Admin_icon=
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
mstoremarkup=01:radio::1:
mstoreparser=02:callback:cot_get_parsers():none:
mstorecount_admin=03:radio::0:
mstoreautovalidate=04:radio::1:
mstoremaxlistsperpage=06:select:5,10,15,20,25,30:30:
mstoretitle_page=07:string::{TITLE} - {CATEGORY}:
mstoreblacktreecatspage=10:string:::Category codes (black list codes page structure as system, unvalidated e.t.c)
[END_COT_EXT_CONFIG]

[BEGIN_COT_EXT_CONFIG_STRUCTURE]
mstoreorder=01:callback:cot_mstore_config_order():title:
mstoreway=02:select:asc,desc:asc:
maxrowsperpage=03:string::30:
mstoretruncatetext=04:string::0:
mstoreallowemptytext=05:radio::0:
mstorekeywords=06:string:::
mstoremetatitle=07:string:::
mstoremetadesc=08:string:::
mstoremaxlistsperpage=09:select:5,10,15,20,25,30:30:
[END_COT_EXT_CONFIG_STRUCTURE]
==================== */