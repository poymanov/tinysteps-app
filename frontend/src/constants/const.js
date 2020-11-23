export const AppRoute = {
    ROOT: `/`,
    GOALS: `/goals`,
    TEACHERS: `/teachers`,
    REQUEST: `/request`,
    REGISTRATION: `/registration`
};

export const APIRoute = {
    GOALS_ACTIVE: `/goals/show/all/active`,
    TEACHERS_ACTIVE: `/teachers/show/all/active`,
    TEACHERS_ACTIVE_BY_GOAL: `/teachers/show/all/active/goal`,
    GOAL: `/goals/show/one/alias`,
    TEACHER: `/teachers/show/one/alias`,
    GOALS_BY_TEACHER: `/teachers/goal/show/all`,
    REGISTRATION: `/auth/signup`
};

export const HttpCode = {
    CREATED: 201
};

export const ALERTS = {
    SUCCESS_REGISTRATION: {
        type: `success`,
        message: `Регистрация успешно завершена. На ваш адрес отправлено письмо для подтверждения профиля.`
    }
};
