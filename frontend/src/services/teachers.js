export const buildTeacher = (teacherData) => {
    return {
        id: teacherData.id,
        alias: teacherData.alias,
        name: {
            first: teacherData.name.first,
            last: teacherData.name.last,
            full: teacherData.name.first + ` ` + teacherData.name.last
        },
        photo: `https://i.pravatar.cc/300?u=${teacherData.id}`,
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
