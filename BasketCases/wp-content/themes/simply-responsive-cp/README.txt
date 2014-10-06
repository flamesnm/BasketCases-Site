{\rtf1\ansi\ansicpg1252\cocoartf949\cocoasubrtf540
{\fonttbl\f0\fnil\fcharset0 LucidaSans-Demi;\f1\fnil\fcharset0 LucidaSans;}
{\colortbl;\red255\green255\blue255;}
\margl1440\margr1440\vieww15680\viewh14220\viewkind0
\pard\tx566\tx1133\tx1700\tx2267\tx2834\tx3401\tx3968\tx4535\tx5102\tx5669\tx6236\tx6803\ql\qnatural\pardirnatural

\f0\b\fs34 \cf0 YouTube Video & Vimeo Video 
\f1\b0\fs28 (optional)\
\
1. Go to your admin area - Classipress->Custom Fields->Add New\
\
Either\
2a. Create a new custom 
\f0\b YouTube ID
\f1\b0  field, ensuring the meta name is 
\f0\b cp_youtube_id
\f1\b0 \
\
Or\
2b. Create a new custom 
\f0\b Vimeo ID
\f1\b0  field, ensuring the meta name is 
\f0\b cp_vimeo_id\
\

\f1\b0 You may create both video custom fields if you wish to allow the option for your customers to add video in either format.\
\
Both Field Type(s): should be TEXT BOX to allow your customers to type in the Video ID (identifier).\
\
You will need to instruct your customers or add a tooltip for your customers explaining that they ONLY need to add the video identifier and NOT the whole URL link, like so...\
\
If the video link is 
\f0\b http://www.youtube.com/watch?v=5PSNL1qE6VY
\f1\b0 \
\
Your customers ONLY need to enter the last part (the identifier): 
\f0\b 5PSNL1qE6VY\

\f1\b0 \
\
To help your customers, in your Field Tooltip you could put:
\f0\b  \
YouTube ID (please enter the video ID only, not the full URL link)\

\f1\b0 and maybe give an example.\
\
PS. You can change the custom field titles AFTER you have setup the custom fields.\
eg: You may want to call them 
\f0\b Youtube Identifier
\f1\b0  and 
\f0\b Vimeo Identifier
\f1\b0  to make it clearer for your customers (up to you).\
\
3. Next, create or edit your form layouts, Classipress->Form Layouts->\
Either Add New form or edit existing form layouts to include the 
\f0\b YouTube Video
\f1\b0  & 
\f0\b Vimeo Video
\f1\b0  custom fields in any category of your choice. Then Simply add your new custom field(s) to your form(s).\
\
4. That's it! Everything else is built into the child theme ready for you to use (if you so wish).\
\
For those of you who wish to add video links to blog post and/or pages via the admin, you may do this by simply adding a shortcode as follows.\
\
For a YouTube video:\
[
\f0\b responsive-video identifier="5PSNL1qE6VY"]
\f1\b0 \
\
For a Vimeo video:\
[
\f0\b responsive-vimeo identifier="24715531"]
\f1\b0 \
\
\
\
\

\f0\b\fs34 Price Negotiable 
\f1\b0\fs28 (optional)\
\
1. Go to your admin area - Classipress->Custom Fields->Add New\
\
2. Create a new custom 
\f0\b Price Negotiable
\f1\b0  field, ensuring the meta name is 
\f0\b cp_price_negotiable
\f1\b0  (!important)\
Field Type: should be CHECKBOXES\
\
3. Next, create or edit your form layout: Classipress->Form Layouts->\
Either Add New form or edit any existing form layouts to include the 
\f0\b Price Negotiable
\f1\b0  custom field.\
\
Simply add your new custom field to your form(s).\

\f0\b DO NOT MAKE THIS FIELD REQUIRED!
\f1\b0 \
\
4. That's it! Everything else is built into the child theme ready for you to use (if you so wish).\
\
For more info on this, see the original tutorial here:\
http://forums.appthemes.com/classipress-general-discussion/***price-negotiable****-sign-listings-30450/\
\
\

\f0\b \
\

\fs34 Non display of Price Tag when no price is entered on ads and the featured slider 
\f1\b0\fs28 \
\
For this to function correctly, go to your admin area - Classipress->Form Layouts and set the price field as 
\f0\b NOT REQUIRED 
\f1\b0 (!important).\
\
\
\
\

\f0\b\fs34 Creating your own "Thank you for your enquiry" page
\f1\b0\fs28 \
\
1. Go to your admin area - Pages->Add New and create a page with the title of "Thank you" (!important).\
\
2. Add  your own thank you message and style it however you wish.\
\
To start you off, you may use the following code in the content of your page\
(please ensure you paste this code in the TEXT tab, not the VISUAL tab)...\
\
<h2><span class="red">Thank you for your email enquiry.</span></h2>\
\
<h2>We will get back to you shortly.</h2>\
<p class="small">If you don't hear back from us within 48 hrs, please check your spam box.</p>\
\
<span><a href="javascript:history.back()">&larr; Go Back</a></span>\
\
There is an image you can upload, which you can see on the demo, sitting inside the images folder. It is called "tku.png"\
\
\
\
We hope these instructions are clear and cover everything you need to set up these extra features. If not, please do not hesitate to contact us: fabtalentmedia@gmail.com\
\
Good luck with your website. It's not going to be easy, but anything that is worth worth while is going to take time and dedication. Be lucky.\
\
\
}