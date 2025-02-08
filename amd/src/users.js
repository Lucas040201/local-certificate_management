import $ from 'jquery';
import Templates from "core/templates";
import ModalDefault from 'core/modal';
import ModalEvents from 'core/modal_events';
import {get_string as getString} from 'core/str';
import ModalSaveCancel from 'core/modal_save_cancel';
import Utils from 'local_certificate_management/utils';
import Repository from 'local_certificate_management/repository';


const getParams = async root => {
    return {
        courseId: Number(root.attr('data-courseid')),
        limit: Number(root.attr('data-limit')),
        page: Number(root.attr('data-page')),
        search: root.find('#search-param').val() || '',
        sort: root.find('#order-param').val() || '',
    };
}

const usersComponents = {
    notFound: 'local_certificate_management/components/users/table-row-not-found',
    empty: 'local_certificate_management/components/users/table-row-empty',
    content: 'local_certificate_management/components/users/table-row'
}

const loadUsers = async (root, seeMore = false) => {
    try {
        const params = await getParams(root);
        const data = await Repository.retrieveUsers(params);

        let templateToLoad = usersComponents.content;

        if (!seeMore && !data.users.length && !params.search) {
            templateToLoad = usersComponents.empty;
        }

        if (!seeMore && !data.users.length && params.search) {
            Utils.disableButton(root);
            templateToLoad = usersComponents.notFound;
        }

        if (!seeMore) {
            root.find('.users-content').empty();
            root.find('.total-users').text(0);
        }

        const html = await Templates.render(templateToLoad, {users: data.users});

        root.find('.total-users').text(data.total)

        root.find('.users-content').append(html);

        const coursesLength = root.find('.users-item').length;

        if (data.total === coursesLength) {
            Utils.disableButton(root);
        } else if (root.find('.see_more').hasClass('hidden') && data.total > coursesLength) {
            Utils.activeButton(root);
        }
        await issueCertificate(root);
    } catch (error) {
        console.log(error);
        root.find('.users-content').empty();
        root.find('.total-users').text(0);
        const html = await Templates.render(usersComponents.empty, {});
        Utils.disableButton(root);
        root.find('.users-content').append(html);
    }

}

const issueCertificate = async root => {
    root.find('.btn-certificate').off();
    root.find('.btn-certificate').on('click', async clickEvent => {
        const response = await Repository.retrieveTemplates();
        const userId = $(clickEvent.currentTarget).attr('data-userid');
        const userFullName = $(clickEvent.currentTarget).attr('data-username');
        const modal = await ModalSaveCancel.create({
            title: await getString('modal_issue_certificate_title', 'local_certificate_management'),
            body: Templates.render('local_certificate_management/components/modal/select_certificate', {templates: response.templates}),
            isVerticallyCentered: true,
        });

        modal.getRoot().on(ModalEvents.save, async saveEvent => {
            const selectValue = $(saveEvent.currentTarget).find('.chose-template').val();
            const params = await getParams(root);

            Repository.issueCertificate({
                templateId: Number(selectValue),
                courseId: Number(params.courseId),
                userId: Number(userId),
            }).then(async () => {
                await ModalDefault.create({
                    title: await getString('modal_certified_issued_with_success_title', 'local_certificate_management'),
                    body: await getString('modal_certified_issued_with_success_body', 'local_certificate_management', userFullName),
                    show: true,
                    isVerticallyCentered: true
                });
            }).catch(async () => {
                await ModalDefault.create({
                    title: await getString('modal_certified_issued_with_error_title', 'local_certificate_management'),
                    body: await getString('modal_certified_issued_with_error_body', 'local_certificate_management', userFullName),
                    show: true,
                    isVerticallyCentered: true
                });
            });
        });

        await modal.show();
    });
}
const init = async root => {
    await loadUsers(root);

    let timeout;
    root.find('#search-param').on('keyup', e => {

        clearTimeout(timeout);

        timeout = setTimeout(async () => {
            await loadUsers(root);
        }, 2000);
    });

    root.find('#order-param').on('change', async event => {
        await loadUsers(root);
    });

    root.find('.see_more').on('click', async function () {
        const newPage = Number(root.attr('data-page')) + 1;
        root.attr('data-page', newPage);
        await loadUsers(root, true);
    });
}

export default {
    init
};