export const buildGoal = (goalData) => {
    return {
        id: goalData.id,
        name: goalData.name,
        alias: goalData.alias,
        sort: goalData.sort,
        status: goalData.sort,
        createdAt: goalData.created_at,
    };
};

export const buildGoals = (goalsData) => {
    const goals = [];

    goalsData.forEach((item) => {
        goals.push(buildGoal(item));
    });

    return goals;
};
