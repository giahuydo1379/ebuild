<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backpack Crud Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the CRUD interface.
    | You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */

    // Forms
    'save_action_save_and_new' => 'Lưu và thêm mới',
    'save_action_save_and_edit' => 'Lưu và chỉnh sửa',
    'save_action_save_and_back' => 'Lưu và thoát',
    'save_action_changed_notification' => 'Default behaviour after saving has been changed.',

    // Create form
    'add'                 => 'Thêm',
    'back_to_all'         => 'Danh sách ',
    'cancel'              => 'Hủy',
    'add_a_new'           => 'Thêm mới',

    // Edit form
    'edit'                 => 'Chỉnh sửa',
    'save'                 => 'Lưu',

    // Revisions
    'revisions'            => 'Revisions',
    'no_revisions'         => 'No revisions found',
    'created_this'         => 'created this',
    'changed_the'          => 'changed the',
    'restore_this_value'   => 'Restore this value',
    'from'                 => 'from',
    'to'                   => 'to',
    'undo'                 => 'Undo',
    'revision_restored'    => 'Revision successfully restored',
    'guest_user'           => 'Guest User',

    // Translatable models
    'edit_translations' => 'EDIT TRANSLATIONS',
    'language'          => 'Ngôn ngữ',

    // CRUD table view
    'all'                       => 'Tất cả ',
    'in_the_database'           => 'trong cơ sở dữ liệu',
    'list'                      => 'Danh sách',
    'actions'                   => 'Chức năng',
    'preview'                   => 'Xem',
    'delete'                    => 'Xóa',
    'admin'                     => 'Quản trị',
    'details_row'               => 'This is the details row. Modify as you please.',
    'details_row_loading_error' => 'There was an error loading the details. Please retry.',

        // Confirmation messages and bubbles
        'delete_confirm'                              => 'Bạn có chắc chắn muốn xóa?',
        'delete_confirmation_title'                   => 'Thông báo',
        'delete_confirmation_message'                 => 'Đã xóa thành công.',
        'delete_confirmation_not_title'               => 'Không xóa',
        'delete_confirmation_not_message'             => "There's been an error. Your item might not have been deleted.",
        'delete_confirmation_not_deleted_title'       => 'Not deleted',
        'delete_confirmation_not_deleted_message'     => 'Nothing happened. Your item is safe.',

        // DataTables translation
        'emptyTable'     => 'Không có dữ liệu',
        'info'           => 'Hiển thị từ _START_ tới _END_ của _TOTAL_ dòng',
        'infoEmpty'      => 'Hiển thị 0 tới 0 của 0 dòng',
        'infoFiltered'   => '(filtered from _MAX_ total entries)',
        'infoPostFix'    => '',
        'thousands'      => ',',
        'lengthMenu'     => '_MENU_ dòng mỗi trang',
        'loadingRecords' => 'Loading...',
        'processing'     => 'Đang lấy dữ liệu...',
        'search'         => 'Tìm kiếm: ',
        'zeroRecords'    => 'No matching records found',
        'paginate'       => [
            'first'    => 'Đầu',
            'last'     => 'Cuối',
            'next'     => 'Tiếp',
            'previous' => 'Trước',
        ],
        'aria' => [
            'sortAscending'  => ': activate to sort column ascending',
            'sortDescending' => ': activate to sort column descending',
        ],

    // global crud - errors
        'unauthorized_access' => 'Unauthorized access - you do not have the necessary permissions to see this page.',
        'please_fix' => 'Please fix the following errors:',

    // global crud - success / error notification bubbles
        'insert_success' => 'Thêm mới thành công.',
        'update_success' => 'Chỉnh sửa thành công.',

    // CRUD reorder view
        'reorder'                      => 'Sắp xếp lại',
        'reorder_text'                 => 'Sử dụng chuột kéo và thả để sắp xếp lại menu.',
        'reorder_success_title'        => 'Thành công',
        'reorder_success_message'      => 'Vị trí menu thay đổi đã được lưu lại.',
        'reorder_error_title'          => 'Lỗi',
        'reorder_error_message'        => 'Vị trí menu thay đổi chưa được lưu lại.',

    // CRUD yes/no
        'yes' => 'Yes',
        'no' => 'No',

    // CRUD filters navbar view
        'filters' => 'Filters',
        'toggle_filters' => 'Toggle filters',
        'remove_filters' => 'Remove filters',

    // Fields
        'browse_uploads' => 'Browse uploads',
        'clear' => 'Xóa',
        'page_link' => 'Page link',
        'page_link_placeholder' => 'http://example.com/your-desired-page',
        'internal_link' => 'Internal link',
        'internal_link_placeholder' => 'Internal slug. Ex: \'admin/page\' (no quotes) for \':url\'',
        'external_link' => 'External link',
        'choose_file' => 'Choose file',

    //Table field
        'table_cant_add' => 'Cannot add new :entity',
        'table_max_reached' => 'Maximum number of :max reached',

    // File manager
    'file_manager' => 'Quản lý File',
];
