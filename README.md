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

see https://github.com/catalyst-app/Catalyst/issues/9

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
