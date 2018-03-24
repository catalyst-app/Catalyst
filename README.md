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

GitHub: https://github.com/catalyst-app/Catalyst

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
- [x] SideNav doesn't work on first load
- [x] Dropdown `.active`
- [x] Autocomplete on settings page
- [ ] SideNav needs dividers (what i have doesn't cut it)
- [x] Emoji no work (thanks foxxo)
- [x] Login is case sensitive username
- [x] Something fucky with the color picker that disallows certain *valid* colors
- [x] Very light colors are hard on navbar and links
  - [x] ~~shadow?~~
- [x] ~~Somethings really weird with the dragging/dropping/sorting plugins, seems to be worsening, requiring bad overrides~~ Improper usage
  - [x] ~~New library?~~
  - [x] Longboi chips
  - [x] Commission types
  - [x] Images
- [x] ~~Default images of something do not stay the same color when viewed on another page (e.g. character displayed on dashboard)~~

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
* Strong security - two factor authentication and constant verifications site-wide

# Basic Feature List

- [ ] Forms
  - [ ] Structure
    - [ ] Highly extensible and versatile class system
  - [ ] AJAX
  - [ ] HTML generation
    - [ ] Move to pQuery?
  - [ ] JS validation
  - [ ] Server-side validation
  - [ ] Fields
    - [ ] Abstract
    - [ ] Captcha
      - [ ] Development mode
    - [ ] Checkbox
      - [ ] Label with HTML
    - [ ] Color Picker
    - [ ] Password fields
      - [ ] Confirmation password field
    - [ ] Text field
      - [ ] Email field
    - [ ] Hidden input field
    - [ ] Image field
      - [ ] ~~Inline rendering/preview?~~
    - [ ] Multiple image field
    - [ ] Multiple image field with preview and options
      - [ ] Reorder
      - [ ] Drag and drop uploading
      - [ ] Ability to change the credit/character inner field
      - [ ] Make it so it accepts a Form itself?
    - [ ] Numeric field
      - [ ] iOS kbd support?
    - [ ] Select field
    - [ ] Static HTML
      - [ ] Maybe a better p element thing for standardization? idk
    - [ ] Prefilling of values
    - [ ] Toggleable button clumps
      - [ ] Multiple/singular allowed
    - [ ] Toggleable button set of sets
    - [ ] Custom sets of forms that can be added/removed
    - [ ] Modifier generator
    - [ ] Radio buttons
  - [ ] Uploading images safely
    - [ ] Token handling
    - [ ] MIME type handling
  - [ ] Completion actions
    - [ ] Abstract
    - [ ] Auto-closing modal
    - [ ] JS function on data
    - [ ] Redirection
      - [ ] Redirect based on data
    - [ ] Conditional
  - [ ] Repository
    - [ ] Pretty up with traits
- [ ] Images
  - [ ] Better standardization
  - [ ] Versatile class
  - [ ] Handling of not-found errors
  - [ ] Generate card
    - [ ] With title/content
    - [ ] With HTML
    - [ ] With ribbon
  - [ ] Generate circular image
  - [ ] Handle pixel art
  - [ ] Traits
  - [ ] MIME handling class
  - [ ] Folders
- [ ] Social media integrations
  - [ ] Trait?
  - [ ] Addition forms
    - [ ] Links
      - [ ] Auto-guess network
    - [ ] Text
  - [ ] Moving
  - [ ] Fix issue on dragging mirrors
- [ ] Messages\*
  - [ ] Threading
  - [ ] Replies
  - [ ] Referencing commissions
  - [ ] Handling to/fro
  - [ ] Composition forms
  - [ ] System user
- [ ] Page stuff
  - [ ] Centralized navbar
  - [ ] Centralized footer
    - [ ] Add stats?
  - [ ] UniversalFunctions/Values cleanup
- [ ] User
  - [ ] TOTP authentication
    - [ ] Key provisioning
    - [ ] Checking with potentially bad time
    - [ ] QR code generation with that weird library
    - [ ] Clearing login well
  - [ ] Settings form
    - [ ] Requiring old password
    - [ ] Deactivation
- [ ] Static analysis
  - [ ] PHPStan
  - [ ] Sublime build systems
- [ ] Database
  - [ ] Central database class for DBH
  - [ ] Column classes
  - [ ] Tables reference map
  - [ ] Database model? trait
  - [ ] Queries
    - [ ] Abstract
    - [ ] SELECT
      - [ ] DISTINCT
      - [ ] Custom columns
    - [ ] DELETE
    - [ ] INSERT
      - [ ] Multiple inserts
    - [ ] REPLACE
    - [ ] UPDATE
  - [ ] Clauses
    - [ ] ~~Abstract~~ Interface
    - [ ] WHERE
    - [ ] GROUP BY
    - [ ] ORDER BY
      - [ ] Multiple
    - [ ] JOIN
      - [ ] 4 types
- [ ] Commission types
  - [ ] Creation form
    - [ ] Modifiers
    - [ ] Payment types
    - [ ] Stages
  - [ ] Editing
  - [ ] Deletion/marking correctly
  - [ ] Handling of images/examples
  - [ ] Frontend UI for actions that can be performed
  - [ ] Better UI for displaying it on artist page?
  - [ ] Rearranging
  - [ ] Trades
  - [ ] Wishlists
    - [ ] Addition
    - [ ] Removal
    - [ ] Purging?
- [ ] Commissions\*
  - [ ] Handling of trades
  - [ ] NSFW correctly
  - [ ] Character choosing
    - [ ] Multiple?
    - [ ] Allow other public ones (send the owner a message)
  - [ ] Quote process
    - [ ] Request -> quote -> approval/denial -> re-request, etc
  - [ ] Adding payment proof at any time
  - [ ] Stage workflow  
    - [ ] Fuzzy estimations
    - [ ] Concrete dates
    - [ ] Just tracking, no dates
  - [ ] Work in progresses
  - [ ] Pretty table to show in progress
  - [ ] Review
    - [ ] Of client
    - [ ] Of creator
    - [ ] Starts + comment
    - [ ] Recent clients/commissioned list
- [ ] Searching
  - [ ] Tage (attributes)
  - [ ] Artist fields
  - [ ] Commission type fields themselves
  - [ ] Reviews
- [ ] Writing
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

# Additional Features

**User Account**  

- [ ] Email verification timeout
- [ ] Password reset
- [ ] Better suspended/disabled notice
- [ ] Integration Profile Photos
- [ ] Reporting spam/fake/stolen/inappropriate
- [ ] Examples

**Dashboard/User Profile**  

- [ ] Statistics
- [ ] Badges

**Characters**  

- [ ] Report spam/fake/stolen/inappropriate
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
- [ ] Pretty 404's
- [ ] Messages to email
- [ ] Dark theme
- [ ] Import images from FA and similar
- [ ] Localization
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
