<?php

namespace App\Enums;

class UserEnum extends BaseEnum
{
    public const PATH_FILE_SAVE = 'public/Blog/image/avatars';

    public const PATH_FILE_DB = 'storage/Blog/image/avatars/';

    // public const DOMAIN_PATH = 'http://localhost:99/'; // docker
    // public const DOMAIN_PATH = 'http://localhost:8000/'; // laragon

    public const DOMAIN_CLIENT = 'http://localhost:4200/';

    public const DOMAIN_PATH = 'https://vanmanh.azurewebsites.net/'; // azure

    public const FORGOT_FORM_USER = 'https://react-vercel-95yac2b5v-vanmanh-react.vercel.app/user/forgot-form?token=';

    public const FORGOT_FORM_ADMIN = 'https://react-vercel-95yac2b5v-vanmanh-react.vercel.app/admin/forgot-form?token=';

    public const VERIFY_MAIL_USER = 'https://react-vercel-95yac2b5v-vanmanh-react.vercel.app/user/verify-email?token=';

    public const VERIFY_MAIL_ADMIN = 'https://react-vercel-95yac2b5v-vanmanh-react.vercel.app/admin/verify-email?token=';
}
