# To anyone inspecting this folder for various fun things:  
## **THESE CANNOT BE USED WITH THE NORMAL API.  THEY DO NOT TAKE, NOR PROVIDE, KEYS.**


### Potential error codes for these endpoints:

#### Overall

- **99901** - No user is logged in
- **99902** - A user is already logged in

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
- **90106** - No captcha response was sent
- **90107** - An invalid captcha response was sent
- **90108** - This account has been suspended
- **90109** - This account has been deactivated
- **90110** - TOTP Challenge required
