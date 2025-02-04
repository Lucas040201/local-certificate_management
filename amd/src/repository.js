import Ajax from 'core/ajax';


const retrieveCourses = obj => {
    const request = {
        methodname: 'local_certificate_management_retrieve_courses',
        args: {
            ...obj
        }
    };

    return Ajax.call([request])[0];
};

export default {
    retrieveCourses
};