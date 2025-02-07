import Course from 'local_certificate_management/course';
import Users from 'local_certificate_management/users';

export const initCourse = async (root) => {
    await Course.init(root);
};
export const initUsers = async root => {
    await Users.init(root);
}