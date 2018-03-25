# To anyone inspecting this folder for various fun things:  
## **THESE ENDPOINTS CANNOT BE USED WITH THE NORMAL API.  THEY DO NOT TAKE, NOR PROVIDE, API KEYS.**
### Using these endpoints inside your own application without prior approval from Catalyst will result in account termination and applicable banning

### Potential error codes for these endpoints:

#### Overall

- **99901** - No user is logged in
- **99902** - A user is already logged in
- **99903** - You are not an artist

#### Email List

- **90001** - No email was passed
- **90002** - Invalid email was passed
- **90003** - No context was passed
- **90004** - An invalid context was passed

#### Login

- **90101** - No username was passed
- **90102** - Invalid username
- **90103** - The username does not exist
- **90104** - No password was passed
- **90105** - An incorrect password was passed
- **90106** - No CAPTCHA response was sent
- **90107** - An invalid CAPTCHA response was sent
- **90108** - This account has been suspended
- **90109** - This account has been deactivated
- **90110** - TOTP Challenge required

#### TOTP Login

- **90201** - There are no active TOTP logins
- **90202** - No code was passed
- **90203** - An invalid code was passed
- **90204** - The code is incorrect

#### Register

- **90301** - Username was not passed
- **90302** - Username is invalid
- **90303** - Username is already in use
- **90304** - Nickname was not passed
- **90305** - Nickname is invalid
- **90306** - Email was not passed
- **90307** - Email is invalid
- **90308** - Email is already in use
- **90309** - Password was not passed
- **90310** - Password does not meet requirements
- **90311** - Password confirmation was not passed
- **90312** - Password confirmation does not match the provided password
- **90313** - Color was not sent
- **90314** - Color is not a valid color
- **90315** - Profile picture is too large
- **90316** - Profile picture is not an image
- **90317** - Profile picture is invalid
- **90318** - NSFW profile picture checkbox is invalid
- **90319** - NSFW access checkbox is invalid
- **90320** - Terms of service agreement value is invalid
- **90321** - Terms of service was not agreed to
- **90322** - CAPTCHA response was not sent
- **90323** - CAPTCHA is invalid
- **90324** - @catalystapp.co emails are not allowed

#### Email Verification

- **90401** - Token was not passed
- **90402** - Token is invalid
- **90403** - CAPTCHA response was not sent
- **90404** - CAPTCHA is invalid
- **90405** - Email is already verified

#### Settings

- **90501** - Username was not passed
- **90502** - Username is invalid
- **90503** - Username is in use by another user
- **90504** - Nickname was not passed
- **90505** - Nickname is invalid
- **90506** - Email was not passed
- **90507** - Email is invalid
- **90508** - Email is already in use
- **90509** - Password was not passed
- **90510** - Password does not meet requirements
- **90511** - Password confirmation was not passed
- **90512** - Password confirmation does not match the provided password
- **90513** - Two factor authentication checkbox is invalid
- **90514** - Color was not sent
- **90515** - Color is not a valid color
- **90516** - Profile picture is too large
- **90517** - Profile picture is not an image
- **90518** - Profile picture is invalid
- **90519** - NSFW profile picture checkbox is invalid
- **90520** - NSFW access checkbox is invalid
- **90521** - Old password was not passed
- **90522** - Old password is incorrect
- **90523** - @catalystapp.co emails are not allowed

#### Deactivate

- **90601** - No username was passed
- **90602** - This username is not yours
- **90603** - No password was passed
- **90604** - An incorrect password was passed

#### Social Media Addition

- **90701** - Invalid destination (user isn't an artist?)
- **90702** - Missing label
- **90703** - Invalid label
- **90704** - URL/email not passed (link only)
- **90705** - URL/email is invalid (link only)
- **90706** - This domain is not allowed (link only)
- **90707** - IP addresses are not allowed (link only)
- **90708** - URL is not a valid scheme (http,https) (link only)
- **90709** - URL inline authentication is disallowed (link only)
- **90710** - Non-standard ports are disallowed (link only)
- **90711** - "javascript:" starts the url string (link only)
- **90712** - No social media type passed ("other" only)
- **90713** - Invalid social media type passed ("other" only)

#### New Character

- **90801** - No name passed
- **90802** - Invalid name passed
- **90803** - No description passed
- **90804** - Invalid description
- **90805** - Files too large
- **90806** - Not an image
- **90807** - Invald image
- **90808** - Color was not passed
- **90809** - Invalid color
- **90810** - No public checkbox value passed
- **90811** - Invalid publicity checkbox value

#### Delete Character

- **90901** - Missing character token
- **90902** - Invalid character token
- **90903** - This character is not yours

#### New Character

- **91001** - No name passed
- **91002** - Invalid name passed
- **91003** - No description passed
- **91004** - Invalid description
- **91005** - Files too large
- **91006** - Not an image
- **91007** - Invald image
- **91008** - Color was not passed
- **91009** - Invalid color
- **91010** - No public checkbox value passed
- **91011** - Invalid publicity checkbox value
- **91012** - Invalid token or bad ownership

#### Undelete artist page

- **91101** - User is currently an artist
- **91102** - User never was an artist

#### Create artist page

- **91201** - Missing name
- **91202** - Name too long
- **91203** - Missing URL
- **91204** - Invalid URL
- **91205** - URL is already in use
- **91206** - Missing description
- **91207** - Invalid description
- **91208** - Missing image
- **91209** - Invalid image
- **91210** - Image is too large
- **91211** - Missing color
- **91212** - Invalid color
- **91213** - Missing ToS
- **91214** - Invalid ToS
- **91215** - Already an artist

#### Delete artist page

- **91301** - Current user is not an artist

#### Edit artist page

- **91401** - Missing name
- **91402** - Name too long
- **91403** - Missing URL
- **91404** - Invalid URL
- **91405** - URL is already in use
- **91406** - Missing description
- **91407** - Invalid description
- **91408** - Missing image
- **91409** - Invalid image
- **91410** - Image is too large
- **91411** - Missing color
- **91412** - Invalid color
- **91413** - Missing ToS
- **91414** - Invalid ToS
- **91415** - Not an artist

#### New commission type

- **91501** - Missing name
- **91502** - Invalid name
- **91503** - Missing blurb
- **91504** - Invalid blurb
- **91505** - Missing description
- **91506** - Invalid description
- **91507** - Missing base cost
- **91508** - Invalid base cost
- **91509** - Missing usd cost
- **91510** - Invalid usd cost
- **91511** - Missing attrs
- **91512** - Invalid attrs
- **91513** - Invalid physical address checkbox
- **91514** - Invalid accepting quotes checkbox
- **91515** - Invalid accepting requests checkbox
- **91516** - Invalid accepting trades checkbox
- **91517** - Invalid accepting commissions checkbox
- **91518** - Missing modifier structure
- **91519** - Invalid modifier structure
- **91520** - Missing payment structure
- **91521** - Invalid payment structure
- **91522** - Missing stages structure
- **91523** - Invalid stages structure
- **91524** - Files too large
- **91525** - Not an images
- **91526** - Invald images
- **91527** - Not an artist
- **91528** - Stage field empty
- **91529** - Stage field invalid
