import Course from 'local_certificate_management/course';

export const initCourse = async (root) => {
    await Course.init(root);
};
export const init = async (root) => {
    console.log(root);
};
