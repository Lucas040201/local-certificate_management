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

const retrieveUsers = obj => {
    const request = {
        methodname: 'local_certificate_management_retrieve_users',
        args: {
            ...obj
        }
    };

    return Ajax.call([request])[0];
};

const retrieveTemplates = obj => {
    const request = {
        methodname: 'local_certificate_management_retrieve_templates',
        args: {
            ...obj
        }
    };

    return Ajax.call([request])[0];
};

const issueCertificate = async obj => {
    const request = {
        methodname: 'local_certificate_management_issue_certificate',
        args: {
            ...obj
        }
    };

    return Ajax.call([request])[0];
};

export default {
    retrieveCourses,
    retrieveUsers,
    retrieveTemplates,
    issueCertificate,
};