# WayneLifter
#### Installation
1. Set on a PHP 7.3 environment
1. Install Composer with `composer install`
1. Install NPM with `npm i`
1. Run on cmd `php artisan key:generate`
1. Open your navigator or do `php artisan serve`

#### Considerations
- On Config Menu, you will be able to edit the elevators and the steps.
- The App allows up to 999 elevators (for your safety, please, don't try if its not extremely necessary, for the sake of your device)
- There is a 'Floor Number' variable, but currently its useless. (It was though to what you could put on "start floor" and "end floor", but... new ticket)
- The app try to balance the use of every elevator (except when there are three elevators, currently dont know why, could be great to know, but beh... new ticket)
- Because if you create two new tickes, probably you will need to create a third...
- There is available a "noob report". But is so noob, so please, dont look at it. Here every step is isolated, so the app to first step, then restart, second step, then restart, etc.... Yup, very noob :) Don't kill me yet
- Probably, i would refactor some functions on reportController
