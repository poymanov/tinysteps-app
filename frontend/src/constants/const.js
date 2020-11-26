export const AppRoute = {
    ROOT: `/`,
    GOALS: `/goals`,
    TEACHERS: `/teachers`,
    REQUEST: `/request`,
    REGISTRATION: `/registration`,
    CONFIRM_PROFILE: `/profile/confirm`,
    PROFILE_COMMON: `/profile/common`,
    PROFILE_CHANGE_NAME: `/profile/name`,
    LOGIN: `/login`
};

export const APIRoute = {
    GOALS_ACTIVE: `/goals/show/all/active`,
    TEACHERS_ACTIVE: `/teachers/show/all/active`,
    TEACHERS_ACTIVE_BY_GOAL: `/teachers/show/all/active/goal`,
    GOAL: `/goals/show/one/alias`,
    TEACHER: `/teachers/show/one/alias`,
    GOALS_BY_TEACHER: `/teachers/goal/show/all`,
    REGISTRATION: `/auth/signup`,
    TOKEN: `/token`,
    PROFILE: `/profile/show`,
    CHANGE_NAME: `/profile/name`,
};

export const HttpCode = {
    SUCCESS: 200,
    CREATED: 201
};

export const ALERTS = {
    SUCCESS_REGISTRATION: {
        type: `success`,
        message: `Регистрация успешно завершена. На ваш адрес отправлено письмо для подтверждения профиля.`
    },
    SUCCESS_CONFIRM_PROFILE: {
        type: `success`,
        message: `Ваш профиль успешно подтвержден.`
    },
    SUCCESS_CHANGE_NAME: {
        type: `success`,
        message: `Имя успешно изменено.`
    }
};
