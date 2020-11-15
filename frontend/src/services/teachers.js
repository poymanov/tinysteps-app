export const buildTeacher = (teacherData) => {
    return {
        id: teacherData.id,
        alias: teacherData.alias,
        name: {
            first: teacherData.name.first,
            last: teacherData.name.last,
            full: teacherData.name.first + ` ` + teacherData.name.last
        },
        photo: `https://via.placeholder.com/300`,
        description: teacherData.description,
        price: teacherData.price,
        rating: teacherData.rating,
        status: teacherData.status,
        userId: teacherData.user_id,
        createdAt: teacherData.created_at,
    };
};

export const buildTeachers = (teachersData) => {
    const teachers = [];

    teachersData.forEach((item) => {
        teachers.push(buildTeacher(item));
    });

    return teachers;
};
