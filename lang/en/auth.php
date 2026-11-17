<?php

declare(strict_types=1);

return [
    'fields' => [
        'name' => 'Full name',
        'email' => 'Email',
        'password' => 'Password',
        'passwordConfirmation' => 'Repeat the password',
        'new_password' => 'New password',
        'password_hint' => 'At least 8 characters.',
        'remember' => 'Remember me on this device',
    ],
    'login' => [
        'eyebrow' => 'Your account',
        'title' => 'Log in',
        'lead' => 'Guests, restaurant owners and staff: log in with your email.',
        'submit' => 'Log in',
        'forgot' => 'Forgot your password?',
        'register' => 'No account yet? Sign up',
    ],
    'register' => [
        'eyebrow' => 'Your account',
        'title' => 'Create your account',
        'lead' => 'You need it to find your bookings and orders again.',
        'submit' => 'Sign up',
        'login' => 'Already have an account? Log in',
    ],
    'back_to_login' => 'Back to sign in',
    'forgot' => [
        'title' => 'Forgot your password',
        'lead' => 'Enter your account email and we will send you a link to choose a new one.',
        'submit' => 'Send me the link',
        'sent' => 'If the address is registered you will shortly receive an email with the link. Check your spam folder too. The link is valid for 60 minutes.',
        'failed' => 'We could not send the email. Please try again in a few minutes.',
    ],
    'reset' => [
        'title' => 'Choose a new password',
        'lead' => 'Once saved, you are signed in straight away.',
        'submit' => 'Save new password',
    ],
    'confirm' => [
        'title' => 'Confirm your password',
        'lead' => 'To continue with a sensitive operation, please enter your password again.',
        'submit' => 'Confirm',
    ],
    'verify' => [
        'title' => 'Verify your email',
        'lead' => 'We sent you a confirmation link. Open it to activate your account.',
        'submit' => 'Resend the link',
        'resent' => 'We sent you a new link.',
        'logout' => 'Sign out',
    ],
];
