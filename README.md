# Catalyst - Facilitating Commissions

# Table of Contents

- [Introduction](#introduction)
  - [Short Blurb](#short-blurb)
  - [Mission](#mission)
  - [What Sets us Apart](#what-sets-us-apart)
- [How you can Help](#how-you-can-help)
- [Roadmap](#roadmap)
- [Known Bugs](#known-bugs)
- [Feature List](#feature-list)
- [Basic Features](#basic-features)
- [Additional Features](#additional-features)
- [Potential Optimizations](#potential-optimizations)
- [Contact Me](#contact-me)
- [Special Thanks](#special-thanks)

# Introduction

## Short Blurb

Catalyst serves to facilitate the process of commissioning through a simple, unified, and mobile-friendly way for artists to easily list their prices, recieve and track commissions, and much more.

## What Sets us Apart

We are aware that there are many current and planned competitors to us.  However, we plan to provide an excellent service in a way no one else does with some of the following features:

* Easy to search system
* Responsive, mobile-friendly interface
* Messages on various events such as opening of commissions
* Message delivery to E-Mail
* **No social media aspect** - this allows for us to focus on what matters and cut the crap
* Easy integration with other social networks
* Character management
* No fees
* And much more!

# How you can Help

Follow us!  I ask for help often in the Discord and Telegram!

Discord: https://discord.gg/EECUcnT

Telegram: https://t.me/catalystapp and https://t.me/catalystapp_announcements

Twitter: https://twitter.com/catalystapp_co

Patreon: https://patreon.com/catalyst

Ko-Fi: https://ko-fi.com/catalystapp

Instagram: https://instagram.com/catalyst.app

DeviantArt: https://catalystapp.deviantart.com/

Weasyl: https://weasyl.com/~catalystapp/

Fur Affinity: http://furaffinity.net/user/catalystapp

FurryNetwork: https://beta.furrynetwork.com/catalyst/

Google Plus: https://plus.google.com/102762464787584663279

Reddit: https://reddit.com/user/catalystapp

Tumblr: https://catalystapp-co.tumblr.com/

Facebook: https://facebook.com/catalystapp.co

GitHub: https://github.com/smileytechguy/Catalyst

Email: catalyst@catalystapp.co

# Roadmap

- [x] Stage One: Reach out, ensure the service would be useful, that there is a demand
- [ ] **Stage Two: Basic developement, put basic infastructure in place, brand initialization**
  - After this stage the site should be usable
  - Get logo done, get some initial popularity
  - Get that email list and whatnot going
  - FA forums
  - **See [basic features](#basic-features) below**
- [ ] Stage Three: Big time advertising, get some initial users
  - Advertise on e621/fa?
  - PM artists and whatnot, ask them to try it out
  - Incentives
    - Give featured status, maybe a badge thing, list on about us?
    - Early adopters
    - People who contribute and search out bugs
    - People who spread it all over the place
    - **Donators**
- [ ] Stage Four: Additional features
  - Take feature requests, act on them?
  - Maybe provide a meta-forum or something
  - **See [additional features](#additional-features) below**
- [ ] Stage Five: Optomization
  - If my servers don't explode then this could really go somewhere
  - **See [potential optimizations](#potential-optimizations) below**
- [ ] Stage Six: ???
- [ ] Stage Seven: Profit
  - Featured artists and stuff ($$$)
  - Plans for more features?

# Known Bugs

- [ ] CAPTCHAs show message about testing only **NOT AN ISSUE**
- [ ] SideNav doesn't work on first load
- [x] Dropdown `.active`
- [x] Autocomplete on settings page
- [ ] SideNav needs dividers (what i have doesn't cut it)
- [x] Emoji no work (thanks foxxo)
- [x] Login is case sensitive username
- [ ] Something fucky with the color picker that disallows certain *valid* colors
- [ ] Very light colors are hard on navbar and links
  - [ ] shadow?
- [ ] Somethings really weird with the dragging/dropping/sorting plugins, seems to be worsening, requiring bad overrides
  - [ ] New library?
  - [ ] Longboi chips
  - [ ] Commission types
  - [ ] Images
- [ ] Default images of something do not stay the same color when viewed on another page (e.g. character displayed on dashboard)

**Broken Links**

- Navbar things
- Message link on user profile
- Message link on artist profile

# Feature List

* Easy to find artists and their listings
* Artist and user profiles will contain social media information, including streaming indication
* Searchable artist profiles with simple formatting (colors, markdown)
* Examples!  Lots of examples for you to gaze upon before you commission!
* Reviews - commissioners can leave an anonymous rating/comment
  * Additionally, you can view recent commissioners and contact them to ask about their experience
* Characters
  * Easily create a profile for your character full of art, refs, bio, etc and share it with an artist!
* Commission management - easily keep track of commissions and their status, possibly with trello integration
* Intuitive, simple design which is fully mobile-friendly!

# Basic Features

**Highest Priority**  

- [x] Basic class restructure of the base namespace
- [x] Info website setup
- [x] Remove secrets and publicise repo: https://github.com/smileytechguy/Catalyst
- [x] Resolve trademark issues and get a new name
- [x] ~~Find~~ Build a markdown editor

**User Account Fundamentals: Complete**  

- [x] Registration
- [x] Login
- [x] Email Verification
- [x] CAPTCHA on important forms
- [x] Settings/Update
- [x] NSFW profile photo control
- [x] Deactivation

**Dashboard/User Profile**  

- [x] Social Networks
  - [x] Add
  - [x] Re-order
  - [x] Remove
  - [x] Potential XSS
- [x] Artist page widget
- [x] Display scrollability
  - [x] Mousewheel/scroll down?
- [x] Characters
  - [x] A pretty display _that works on mobile_
- [x] Wishlist
  - [x] A pretty display _that works on mobile_
  - [x] Check for deleted
- [ ] Commissions
  - [ ] A pretty table _that works on mobile_

**Characters**  

- [x] Landing page
- [x] Creation
  - [x] Multi-file upload
- [x] Editing
- [x] Editing Images
- [x] Public/private
- [x] NSFW picture handling
- [x] Removal
  - [x] UI
  - [x] Database cascading with existing commissions and such

**Artists**  

- [x] Create a page
  - [x] Check for deleted
- [x] NSFW picture handling
- [x] Edit
  - [x] Social media
    - [x] Display
    - [x] Order
    - [x] New
    - [x] Edit
  - [x] Info
  - [x] Commission types
    - [x] Rearrange
    - [x] Delete
- [x] Show commission types
- [x] Basic information
- [x] Social medias
- [ ] Ratings
- [ ] Recent commissioners

**Commission Type**  

- [x] Create
  - [x] Basic data
  - [x] Modifiers
  - [x] Attributes
    - [x] ~~Global el type?~~
  - [x] Payment opts
  - [x] Stage opts
- [x] Type (for searching)
  - [x] Specie
  - [x] Type
  - [x] SFW
  - [x] Viewing, handle db
- [x] Edit
  - [x] Basic data
    - [x] db
  - [x] Modifiers
    - [x] db
  - [x] Attributes
    - [x] db
  - [x] Payment opts
    - [x] db
  - [x] Stage opts
    - [x] db
  - [x] Images
- [x] View
  - [x] "Collapisble"
  - [x] Basic info
  - [x] NSFW
  - [x] Blurb
  - [x] Modifiers
  - [x] Images
  - [x] Options
  - [x] Payment Options
  - [x] Closed/open
- [x] NSFW handling
- [x] Modifiers
- [x] ~~Creation in a form (collapsible?)~~
- [x] Wishlist

**Commission**  

- [x] Trades?
- [ ] NSFW picture handling
- [ ] Multiple character chooser
- [ ] A neat display for assigned characters
- [ ] Payment
  - [x] Multiple
- [ ] States
  - [ ] Pending approval
  - [ ] **Denied**
  - [ ] Approved; pending payment
  - [ ] Waiting for artist
    - [x] Customizable statuses (lineart, sketch, shading, etc)
  - [ ] Finished
- [ ] WIPs
  - [ ] Multiple
- [ ] Review afterwards
- [ ] Messaging integrations
- [ ] Viewing page
  - [ ] Table+?
  - [ ] For artist
  - [ ] For commissioner
- [ ] Timestamps

**Searching**  

- [ ] Artist name
- [ ] Reviews
- [ ] Prices
- [ ] Attributes
- [ ] Commission type name

**Text things**

- [x] About Page
  - [x] Staff
  - [x] Official accounts of staff
  - [x] Social medias
  - [x] ~~story?~~
  - [x] ~~helpers/testers~~
- [ ] ToS
- [ ] Help center?
  - [ ] Support desk
  - [ ] reporting
  - [ ] DMCA
  - [ ] General rules + code of conduct
  - [ ] Explaination of all features
  - [ ] Verification of artists
  - [ ] Getting started
  - [ ] Glossary/vocabulary
- [ ] FAQ
  - [ ] What does it cost?
  - [ ] What sets us apart?
  - [ ] How can we be free?
  - [ ] Encryption/security
  - [ ] Paying artists

**Universal**  

- [ ] Messages
  - [ ] Threads
- [x] Pixel art handling
- [x] NSFW image handling (fallback)
- [x] Markdown editor
  - [x] Markdown spec
    - [x] Custom containers
    - [x] Color
    - [x] Documentation
  - [x] Live preview
  - [x] ~~Quick buttons~~

# Additional Features

**User Account**  

- [ ] Email verification timeout
- [ ] Password reset
- [ ] Better suspended/disabled notice
- [ ] Integration Profile Photos
- [ ] Reporting spam/fake/stolen/inappropriate
- [ ] Blob-based uploading (show inline)
- [ ] Social media URL form things
- [ ] Drag and drop uploading
- [ ] Let artists write reviews for buyers
- [ ] Example account

**Dashboard/User Profile**  

- [ ] Statistics
- [ ] Badges

**Characters**  

- [ ] Blob-based uploading (show inline)
- [ ] Report spam/fake/stolen/inappropriate
- [ ] Drag and drop uploading
- [ ] Sharing
- [ ] URL access?
- [ ] Example character

**Artists**  

- [ ] Streaming
- [ ] Trello integration
- [ ] Report spam/fake/inappropriate
- [ ] Statistics
- [ ] Send message to those who are watching (have added to wishlist)
- [ ] Statistics for money made
- [ ] Social media URL form things
- [ ] Badges
  - [ ] Verification
- [ ] Experience levels
  - [ ] From reviews?
- [ ] Example page

**Commission Types**  

- [ ] Analytics
- [ ] Trello
- [ ] Direct links
- [ ] Payments
  - [ ] Suggested types
  - [ ] Fee calculators
- [ ] Report spam
- [ ] Schedule opening
- [ ] Slot numbers
- [ ] Writers
- [ ] Example

**Commission**  

- [ ] PayPal cart integration/invoice
- [ ] integration upload
- [ ] Sharing
- [ ] Trello
  - [ ] Access control
- [ ] Blob-based uploading (show inline)
- [ ] Drag and drop uploading
- [ ] Message which it concerns
- [ ] Review flagging
- [ ] Review reply
- [ ] Report
  - [ ] Harrassment
  - [ ] Spam
  - [ ] Scam/fraud
- [ ] Examples
  - [ ] Start
  - [ ] Middle
  - [ ] Review

**Search**  

- [ ] Better syntax (AND, OR, XOR, basic boolean ops)

**Universal**  

- [ ] SFW button (like FA's)
- [ ] Messaging "concerning" attr
- [ ] Pretty 404's
- [ ] Handle input error messages better
- [ ] Messages to email
- [ ] Dark theme
- [ ] Import images from FA and similar
- [ ] Localization
- [ ] Reporting of broken site and such
- [ ] Blacklisting attrs

**Easter eggs**  

- [x] Konami-style bulge
- [ ] Confetti button
- [ ] Some fun about page stuff

# Potential Optimizations

- [x] Single user SQL fetch on normal page load
  - [ ] API calls should not use this

# Developer Notes

This project adheres to PSR-1 and PSR-2 with the following exceptions:
- Indentation is a tab, not spaces,
- Opening braces go on the same line, and
- Closure `function` keywords must not have a space following them.


# Special Thanks

### I'd like to give a special thanks to everyone here for helping in one way or another
#### I'm sure I'm forgetting a lot of people, if you think you should be here let me know!
##### Get yourself on this list by [helping out](#how-you-can-help)

Some of these may become badges or something, who knows  

Discord  

- **@Lykai#2495**
  - For being wonderful :heart:
  - Beta tester
  - Suggestions of critical components
- **@A Hidden Waffle#1348**
  - Beta tester
  - Bug reporter
  - XSS tester
- **@Xepher Corvidae#1055**
  - Beta tester
- **@Abflug#4116**
  - Suggestions of critical components
  - Logo design and advice
  - Beta tester
- **@Altair314#3244**
  - Dealing with me
  - Beta tester
- **@Foxxo#7183**
  - Beta tester
  - Bug reporter
- **@Fire Imp#8729**
  - Beta tester
- **@FUNKENGINE#1011**
  - Beta tester
  - Bug reporter
- **@Raidiancat_909#3307**
  - Logo assistance
  - Beta tester
- **@Kathy#9837**
  - Beta tester
- **@The_Sofa#9595**
  - Beta tester
- **@Tony [Tony’s Turkey Time Tony]#2115**
  - Beta tester
- **@Discombobulation#5558**
  - Beta tester
  - Suggestions

And many others!  See the Discord server's roles for an updated list!

Telegram (see the telegram channel for the most updated list)

- **@Cilith_Furr**
  - Beta tester
- **@KingDingo**
  - Beta tester
- **@Silent_rose23**
  - Beta tester
  - Suggestions
- **@PM_ME_YIFF_PICS** ~~dont actually~~
  - Beta tester
  - Bug reporter
- **@TheGamingWolf**
  - Beta tester
  - Suggestions
- **@JikiScott**
  - Name assistance
  - Beta tester
  - Overall consultant
- **@TwistRatface**
  - Beta tester
  - Suggestions
- **@sprcboi**
  - Beta tester
- **@Pulex**
  - Suggestions
- **@Toish**
  - Help with ~~the hell that is~~ node and webpack for the markdown parser
- **@treepangolin**
  - Being cool and helping
  - Suggesstions!
- **@Scout_the_what**
  - Suggestions
- **@seamonkee**
  - Suggestions
- **@PrinceLumLum**
  - Suggestions
  - Beta testing
