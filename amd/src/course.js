import Repository from 'local_certificate_management/repository';
import Templates from "core/templates";

const getParams = async root => {

}

const courseComponents = {
    notFound: 'local_certificate_management/components/course/table-row-not-found',
    empty: 'local_certificate_management/components/course/table-row-empty',
    content: 'local_certificate_management/components/course/table-row
}

const loadCourses = async (root, seeMore = false) => {
    try {
        const params = getParams(root);
        const courses = Repository.retrieveCourses(params);

        let templateToLoad = courseComponents.content;

        if (!seeMore && !courses.courses.length && !params.search) {
            templateToLoad = courseComponents.empty;
        }

        if (!seeMore && !courses.length && params.search) {
            disableButton(root);
            templateToLoad = courseComponents.notFound;
        }

        const html = await Templates.render(templateToLoad, {courses});

        root.find('.total_students').text(courses.total)

        root.find('.course-activity-time-table-body').append(html);

        const coursesLength = root.find('.courses-item').length;

        if (courses.total === coursesLength) {
            disableButton(root);
        } else if (root.find('.see_more').hasClass('hidden') && courses.total > coursesLength) {
            activeButton(root);
        }
    } catch (error) {
        const html = await Templates.render(courseComponents.empty, {});
        disableButton(root);
        root.find('.course-activity-time-table-body').append(html);
    }

}

function disableButton(root, findClass = 'see_more') {
    root.find(`.${findClass}`).addClass('hidden');
    root.find(`.${findClass}`).attr('disabled', true);
}

function activeButton(root, findClass = 'see_more') {
    root.find(`.${findClass}`).removeClass('hidden');
    root.find(`.${findClass}`).attr('disabled', false);
}

const init = async root => {
    await loadCourses(root);
}

export default {
    init
};