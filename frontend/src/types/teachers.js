import PropTypes from "prop-types";

const teacher = PropTypes.shape({
    id: PropTypes.string.isRequired,
    alias: PropTypes.string.isRequired,
    name: PropTypes.shape({
        first: PropTypes.string.isRequired,
        last: PropTypes.string.isRequired,
        full: PropTypes.string.isRequired,
    }).isRequired,
    photo: PropTypes.string.isRequired,
    description: PropTypes.string.isRequired,
    price: PropTypes.number.isRequired,
    rating: PropTypes.number.isRequired,
    status: PropTypes.string.isRequired,
    userId: PropTypes.string.isRequired,
    createdAt: PropTypes.string.isRequired,
});

const TeacherTypes = {
    item: teacher,
    list: PropTypes.arrayOf(teacher.isRequired)
};

export default TeacherTypes;
