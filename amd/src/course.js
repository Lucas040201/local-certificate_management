import Repository from 'local_certificate_management/repository';
import Templates from "core/templates";

const getParams = async root => {
    return {
        limit: Number(root.attr('data-limit')),
        page: Number(root.attr('data-page')),
        search: root.find('#search-param').val() || '',
        sort: root.find('#order-param').val() || '',
    };
}

const courseComponents = {
    notFound: 'local_certificate_management/components/course/table-row-not-found',
    empty: 'local_certificate_management/components/course/table-row-empty',
    content: 'local_certificate_management/components/course/table-row'
}

const loadCourses = async (root, seeMore = false) => {
    try {
        const params = await getParams(root);
        const data = await Repository.retrieveCourses(params);

        let templateToLoad = courseComponents.content;

        if (!seeMore && !data.courses.length && !params.search) {
            templateToLoad = courseComponents.empty;
        }

        if (!seeMore && !data.courses.length && params.search) {
            disableButton(root);
            templateToLoad = courseComponents.notFound;
        }

        if (!seeMore) {
            root.find('.courses-content').empty();
            root.find('.total-courses').text(0);
        }

        const html = await Templates.render(templateToLoad, {courses: data.courses});

        root.find('.total-courses').text(data.total)

        root.find('.courses-content').append(html);

        const coursesLength = root.find('.courses-item').length;

        if (data.total === coursesLength) {
            disableButton(root);
        } else if (root.find('.see_more').hasClass('hidden') && data.total > coursesLength) {
            activeButton(root);
        }
    } catch (error) {
        root.find('.courses-content').empty();
        root.find('.total-courses').text(0);
        const html = await Templates.render(courseComponents.empty, {});
        disableButton(root);
        root.find('.courses-content').append(html);
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

    let timeout;
    root.find('#search-param').on('keyup', e => {

        clearTimeout(timeout);

        timeout = setTimeout(async () => {
            await loadCourses(root);
        }, 2000);
    });

    root.find('#order-param').on('change', async event => {
       await loadCourses(root);
    });

    root.find('.see_more').on('click', async function () {
        const newPage = Number(root.attr('data-page')) + 1;
        root.attr('data-page', newPage);
        await loadCourses(root, true);
    });
}

export default {
    init
};