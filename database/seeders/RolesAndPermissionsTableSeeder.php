<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laratrust\Models\LaratrustPermission;
use Laratrust\Models\LaratrustRole;
use App\Models\User;

class RolesAndPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Roles
        $admin = LaratrustRole::firstOrCreate(
            [
                'name' => 'admin',
                'display_name' => 'Admin'
            ]
        );
        LaratrustRole::firstOrCreate(
            [
                'name' => 'user',
                'display_name' => 'User'
            ]
        );

        LaratrustRole::firstOrCreate(
            [
                'name' => 'member',
                'display_name' => 'Member'
            ]
        );

        // Permissions
        $accessByAdmin = LaratrustPermission::firstOrCreate(
            [
                'name' => 'admin_view_rp',
                'display_name' => 'Access By Admin',
                'group' => 'Role'
            ]
        );

        $manageViewOrders = LaratrustPermission::firstOrCreate(
            [
                'name' => 'order_view_rp',
                'display_name' => 'Manage View Orders',
                'group' => 'Finance'
            ]
        );
        $manageShowOrders = LaratrustPermission::firstOrCreate(
            [
                'name' => 'order_show_rp',
                'display_name' => 'Manage Show Order',
                'group' => 'Finance'
            ]
        );
        $manageBulkOrders = LaratrustPermission::firstOrCreate(
            [
                'name' => 'order_bulk_rp',
                'display_name' => 'Manage Bulk Order',
                'group' => 'Finance'
            ]
        );
        $manageCreateOrders = LaratrustPermission::firstOrCreate(
            [
                'name' => 'order_create_rp',
                'display_name' => 'Manage Create Order',
                'group' => 'Finance'
            ]
        );
        $manageEditOrders = LaratrustPermission::firstOrCreate(
            [
                'name' => 'order_edit_rp',
                'display_name' => 'Manage Edit Order',
                'group' => 'Finance'
            ]
        );
        $manageDeleteOrders = LaratrustPermission::firstOrCreate(
            [
                'name' => 'order_delete_rp',
                'display_name' => 'Manage Delete Order',
                'group' => 'Finance'
            ]
        );
        $manageViewPayouts = LaratrustPermission::firstOrCreate(
            [
                'name' => 'payout_view_rp',
                'display_name' => 'Manage View Payouts',
                'group' => 'Payout'
            ]
        );
        $manageShowPayouts = LaratrustPermission::firstOrCreate(
            [
                'name' => 'payout_show_rp',
                'display_name' => 'Manage Show Payout',
                'group' => 'Payout'
            ]
        );
        $manageBulkPayouts = LaratrustPermission::firstOrCreate(
            [
                'name' => 'payout_bulk_rp',
                'display_name' => 'Manage Bulk Payout',
                'group' => 'Payout'
            ]
        );
        $manageCreatePayouts = LaratrustPermission::firstOrCreate(
            [
                'name' => 'payout_create_rp',
                'display_name' => 'Manage Create Payout',
                'group' => 'Payout'
            ]
        );
        $manageEditPayouts = LaratrustPermission::firstOrCreate(
            [
                'name' => 'payout_edit_rp',
                'display_name' => 'Manage Edit Payout',
                'group' => 'Payout'
            ]
        );
        $manageDeletePayouts = LaratrustPermission::firstOrCreate(
            [
                'name' => 'payout_delete_rp',
                'display_name' => 'Manage Delete Payout',
                'group' => 'Payout'
            ]
        );
        $manageViewItems = LaratrustPermission::firstOrCreate(
            [
                'name' => 'item_view_rp',
                'display_name' => 'Manage View Items',
                'group' => 'Item'
            ]
        );
        $manageShowItems = LaratrustPermission::firstOrCreate(
            [
                'name' => 'item_show_rp',
                'display_name' => 'Manage Show Item',
                'group' => 'Item'
            ]
        );
        $manageCreateItems = LaratrustPermission::firstOrCreate(
            [
                'name' => 'item_create_rp',
                'display_name' => 'Manage Create Item',
                'group' => 'Item'
            ]
        );
        $manageEditItems = LaratrustPermission::firstOrCreate(
            [
                'name' => 'item_edit_rp',
                'display_name' => 'Manage Edit Item',
                'group' => 'Item'
            ]
        );
        $manageDeleteItems = LaratrustPermission::firstOrCreate(
            [
                'name' => 'item_delete_rp',
                'display_name' => 'Manage Delete Item',
                'group' => 'Item'
            ]
        );
        $manageBulkItems = LaratrustPermission::firstOrCreate(
            [
                'name' => 'item_bulk_rp',
                'display_name' => 'Manage Bulk Item',
                'group' => 'Item'
            ]
        );
        $manageFeedbackItems = LaratrustPermission::firstOrCreate(
            [
                'name' => 'item_feedback_rp',
                'display_name' => 'Manage Feedback Item',
                'group' => 'Item'
            ]
        );
        $manageViewUsers = LaratrustPermission::firstOrCreate(
            [
                'name' => 'user_view_rp',
                'display_name' => 'Manage User View',
                'group' => 'User'
            ]
        );
        $manageCreateUsers = LaratrustPermission::firstOrCreate(
            [
                'name' => 'user_create_rp',
                'display_name' => 'Manage User Create',
                'group' => 'User'
            ]
        );
        $manageEditUsers = LaratrustPermission::firstOrCreate(
            [
                'name' => 'user_edit_rp',
                'display_name' => 'Manage User Edit',
                'group' => 'User'
            ]
        );
        $manageShowUsers = LaratrustPermission::firstOrCreate(
            [
                'name' => 'user_show_rp',
                'display_name' => 'Manage Show Edit',
                'group' => 'User'
            ]
        );
        $manageControlMfaUsers = LaratrustPermission::firstOrCreate(
            [
                'name' => 'mfa_view_rp',
                'display_name' => 'Manage User Control MFA',
                'group' => 'User'
            ]
        );
        $manageDeleteUsers = LaratrustPermission::firstOrCreate(
            [
                'name' => 'user_delete_rp',
                'display_name' => 'Manage User Delete',
                'group' => 'User'
            ]
        );
        $manageBulkUsers = LaratrustPermission::firstOrCreate(
            [
                'name' => 'user_bulk_rp',
                'display_name' => 'Manage Bulk Delete',
                'group' => 'User'
            ]
        );
        $manageControlWallet = LaratrustPermission::firstOrCreate(
            [
                'name' => 'wallet_view_rp',
                'display_name' => 'Manage Bulk Wallet',
                'group' => 'Wallet'
            ]
        );
        $manageEditWallet = LaratrustPermission::firstOrCreate(
            [
                'name' => 'wallet_edit_rp',
                'display_name' => 'Manage Edit Wallet',
                'group' => 'Wallet'
            ]
        );
        $manageViewRoles = LaratrustPermission::firstOrCreate(
            [
                'name' => 'role_view_rp',
                'display_name' => 'Manage View Role',
                'group' => 'Role'
            ]
        );
        $manageCreateRoles = LaratrustPermission::firstOrCreate(
            [
                'name' => 'role_create_rp',
                'display_name' => 'Manage Create Role',
                'group' => 'Role'
            ]
        );
        $manageEditRoles = LaratrustPermission::firstOrCreate(
            [
                'name' => 'role_edit_rp',
                'display_name' => 'Manage Edit Role',
                'group' => 'Role'
            ]
        );
        $manageDeleteRoles = LaratrustPermission::firstOrCreate(
            [
                'name' => 'role_delete_rp',
                'display_name' => 'Manage Delete Role',
                'group' => 'Role'
            ]
        );
        $manageViewPermissions = LaratrustPermission::firstOrCreate(
            [
                'name' => 'permission_view_rp',
                'display_name' => 'Manage View Permission',
                'group' => 'Permissions'
            ]
        );
        $manageCreatePermissions = LaratrustPermission::firstOrCreate(
            [
                'name' => 'permission_create_rp',
                'display_name' => 'Manage Create Permission',
                'group' => 'Permissions'
            ]
        );
        $manageEditPermissions = LaratrustPermission::firstOrCreate(
            [
                'name' => 'permission_edit_rp',
                'display_name' => 'Manage Edit Permission',
                'group' => 'Permissions'
            ]
        );
        $manageDeletePermissions = LaratrustPermission::firstOrCreate(
            [
                'name' => 'permission_delete_rp',
                'display_name' => 'Manage Delete Permission',
                'group' => 'Permissions'
            ]
        );
        $manageViewEnquiries = LaratrustPermission::firstOrCreate(
            [
                'name' => 'enquiry_view_rp',
                'display_name' => 'Manage View Enquiries',
                'group' => 'Enquiry'
            ]
        );
        $manageShowEnquiries = LaratrustPermission::firstOrCreate(
            [
                'name' => 'enquiry_show_rp',
                'display_name' => 'Manage Show Enquiry',
                'group' => 'Enquiry'
            ]
        );
        $manageBulkEnquiries = LaratrustPermission::firstOrCreate(
            [
                'name' => 'enquiry_bulk_rp',
                'display_name' => 'Manage Bulk Enquiry',
                'group' => 'Enquiry'
            ]
        );
        $manageCreateEnquiries = LaratrustPermission::firstOrCreate(
            [
                'name' => 'enquiry_create_rp',
                'display_name' => 'Manage Create Enquiry',
                'group' => 'Enquiry'
            ]
        );
        $manageEditEnquiries = LaratrustPermission::firstOrCreate(
            [
                'name' => 'enquiry_edit_rp',
                'display_name' => 'Manage Edit Enquiry',
                'group' => 'Enquiry'
            ]
        );
        $manageDeleteEnquiries = LaratrustPermission::firstOrCreate(
            [
                'name' => 'enquiry_delete_rp',
                'display_name' => 'Manage Delete Enquiry',
                'group' => 'Enquiry'
            ]
        );
        $manageViewTickets = LaratrustPermission::firstOrCreate(
            [
                'name' => 'ticket_view_rp',
                'display_name' => 'Manage View Tickets',
                'group' => 'Ticket'
            ]
        );
        $manageShowTickets = LaratrustPermission::firstOrCreate(
            [
                'name' => 'ticket_show_rp',
                'display_name' => 'Manage Show Ticket',
                'group' => 'Ticket'
            ]
        );
        $manageBulkTickets = LaratrustPermission::firstOrCreate(
            [
                'name' => 'ticket_bulk_rp',
                'display_name' => 'Manage Bulk Ticket',
                'group' => 'Ticket'
            ]
        );
        $manageMeetTickets = LaratrustPermission::firstOrCreate(
            [
                'name' => 'ticket_meet_rp',
                'display_name' => 'Manage Start Meet Ticket',
                'group' => 'Ticket'
            ]
        );
        $manageCreateTickets = LaratrustPermission::firstOrCreate(
            [
                'name' => 'ticket_create_rp',
                'display_name' => 'Manage Create Ticket',
                'group' => 'Ticket'
            ]
        );
        $manageEditTickets = LaratrustPermission::firstOrCreate(
            [
                'name' => 'ticket_edit_rp',
                'display_name' => 'Manage Edit Ticket',
                'group' => 'Ticket'
            ]
        );
        $manageDeleteTickets = LaratrustPermission::firstOrCreate(
            [
                'name' => 'ticket_delete_rp',
                'display_name' => 'Manage Delete Ticket',
                'group' => 'Ticket'
            ]
        );
        $manageViewNewsletters = LaratrustPermission::firstOrCreate(
            [
                'name' => 'newsletter_view_rp',
                'display_name' => 'Manage View Newsletters',
                'group' => 'Newsletter'
            ]
        );
        $manageShowNewsletters = LaratrustPermission::firstOrCreate(
            [
                'name' => 'newsletter_show_rp',
                'display_name' => 'Manage Show Newsletter',
                'group' => 'Newsletter'
            ]
        );
        $manageBulkNewsletters = LaratrustPermission::firstOrCreate(
            [
                'name' => 'newsletter_bulk_rp',
                'display_name' => 'Manage Bulk Newsletter',
                'group' => 'Newsletter'
            ]
        );
        $manageCreateNewsletters = LaratrustPermission::firstOrCreate(
            [
                'name' => 'newsletter_create_rp',
                'display_name' => 'Manage Create Newsletter',
                'group' => 'Newsletter'
            ]
        );
        $manageEditNewsletters = LaratrustPermission::firstOrCreate(
            [
                'name' => 'newsletter_edit_rp',
                'display_name' => 'Manage Edit Newsletter',
                'group' => 'Newsletter'
            ]
        );
        $manageDeleteNewsletters = LaratrustPermission::firstOrCreate(
            [
                'name' => 'newsletter_delete_rp',
                'display_name' => 'Manage Delete Newsletter',
                'group' => 'Newsletter'
            ]
        );
        $manageViewLeads = LaratrustPermission::firstOrCreate(
            [
                'name' => 'lead_view_rp',
                'display_name' => 'Manage View Leads',
                'group' => 'Lead'
            ]
        );
        $manageShowLeads = LaratrustPermission::firstOrCreate(
            [
                'name' => 'lead_show_rp',
                'display_name' => 'Manage Show Lead',
                'group' => 'Lead'
            ]
        );
        $manageBulkLeads = LaratrustPermission::firstOrCreate(
            [
                'name' => 'lead_bulk_rp',
                'display_name' => 'Manage Bulk Lead',
                'group' => 'Lead'
            ]
        );
        $manageCreateLeads = LaratrustPermission::firstOrCreate(
            [
                'name' => 'lead_create_rp',
                'display_name' => 'Manage Create Lead',
                'group' => 'Lead'
            ]
        );
        $manageEditLeads = LaratrustPermission::firstOrCreate(
            [
                'name' => 'lead_edit_rp',
                'display_name' => 'Manage Edit Lead',
                'group' => 'Lead'
            ]
        );
        $manageDeleteLeads = LaratrustPermission::firstOrCreate(
            [
                'name' => 'lead_delete_rp',
                'display_name' => 'Manage Delete Lead',
                'group' => 'Lead'
            ]
        );
        $manageViewNotes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'note_view_rp',
                'display_name' => 'Manage View Notes',
                'group' => 'Note'
            ]
        );
        $manageShowNotes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'note_show_rp',
                'display_name' => 'Manage Show Note',
                'group' => 'Note'
            ]
        );
        $manageBulkNotes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'note_bulk_rp',
                'display_name' => 'Manage Bulk Note',
                'group' => 'Note'
            ]
        );
        $manageCreateNotes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'note_create_rp',
                'display_name' => 'Manage Create Note',
                'group' => 'Note'
            ]
        );
        $manageEditNotes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'note_edit_rp',
                'display_name' => 'Manage Edit Note',
                'group' => 'Note'
            ]
        );
        $manageDeleteNotes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'note_delete_rp',
                'display_name' => 'Manage Delete Note',
                'group' => 'Note'
            ]
        );
        $manageViewContacts = LaratrustPermission::firstOrCreate(
            [
                'name' => 'contact_view_rp',
                'display_name' => 'Manage View Contacts',
                'group' => 'Contact'
            ]
        );
        $manageShowContacts = LaratrustPermission::firstOrCreate(
            [
                'name' => 'contact_show_rp',
                'display_name' => 'Manage Show Contact',
                'group' => 'Contact'
            ]
        );
        $manageBulkContacts = LaratrustPermission::firstOrCreate(
            [
                'name' => 'contact_bulk_rp',
                'display_name' => 'Manage Bulk Contact',
                'group' => 'Contact'
            ]
        );
        $manageCreateContacts = LaratrustPermission::firstOrCreate(
            [
                'name' => 'contact_create_rp',
                'display_name' => 'Manage Create Contact',
                'group' => 'Contact'
            ]
        );
        $manageEditContacts = LaratrustPermission::firstOrCreate(
            [
                'name' => 'contact_edit_rp',
                'display_name' => 'Manage Edit Contact',
                'group' => 'Contact'
            ]
        );
        $manageDeleteContacts = LaratrustPermission::firstOrCreate(
            [
                'name' => 'contact_delete_rp',
                'display_name' => 'Manage Delete Contact',
                'group' => 'Contact'
            ]
        );
        $manageViewAddresses = LaratrustPermission::firstOrCreate(
            [
                'name' => 'address_view_rp',
                'display_name' => 'Manage View Addresses',
                'group' => 'User Address'
            ]
        );
        $manageShowAddresses = LaratrustPermission::firstOrCreate(
            [
                'name' => 'address_show_rp',
                'display_name' => 'Manage Show Address',
                'group' => 'User Address'
            ]
        );
        $manageBulkAddresses = LaratrustPermission::firstOrCreate(
            [
                'name' => 'address_bulk_rp',
                'display_name' => 'Manage Bulk Address',
                'group' => 'User Address'
            ]
        );
        $manageCreateAddresses = LaratrustPermission::firstOrCreate(
            [
                'name' => 'address_create_rp',
                'display_name' => 'Manage Create Address',
                'group' => 'User Address'
            ]
        );
        $manageEditAddresses = LaratrustPermission::firstOrCreate(
            [
                'name' => 'address_edit_rp',
                'display_name' => 'Manage Edit Address',
                'group' => 'User Address'
            ]
        );
        $manageDeleteAddresses = LaratrustPermission::firstOrCreate(
            [
                'name' => 'address_delete_rp',
                'display_name' => 'Manage Delete Address',
                'group' => 'User Address'
            ]
        );
        $manageViewBanks = LaratrustPermission::firstOrCreate(
            [
                'name' => 'bank_view_rp',
                'display_name' => 'Manage View Banks',
                'group' => 'User Bank'
            ]
        );
        $manageShowBanks = LaratrustPermission::firstOrCreate(
            [
                'name' => 'bank_show_rp',
                'display_name' => 'Manage Show Bank',
                'group' => 'User Bank'
            ]
        );
        $manageBulkBanks = LaratrustPermission::firstOrCreate(
            [
                'name' => 'bank_bulk_rp',
                'display_name' => 'Manage Bulk Bank',
                'group' => 'User Bank'
            ]
        );
        $manageCreateBanks = LaratrustPermission::firstOrCreate(
            [
                'name' => 'bank_create_rp',
                'display_name' => 'Manage Create Bank',
                'group' => 'User Bank'
            ]
        );
        $manageEditBanks = LaratrustPermission::firstOrCreate(
            [
                'name' => 'bank_edit_rp',
                'display_name' => 'Manage Edit Bank',
                'group' => 'User Bank'
            ]
        );
        $manageDeleteBanks = LaratrustPermission::firstOrCreate(
            [
                'name' => 'bank_delete_rp',
                'display_name' => 'Manage Delete Bank',
                'group' => 'User Bank'
            ]
        );

        $manageViewKyc = LaratrustPermission::firstOrCreate(
            [
                'name' => 'kyc_view_rp',
                'display_name' => 'Manage View Kyc',
                'group' => 'User Kyc'
            ]
        );
        $manageShowKyc = LaratrustPermission::firstOrCreate(
            [
                'name' => 'kyc_show_rp',
                'display_name' => 'Manage Show Kyc',
                'group' => 'User Kyc'
            ]
        );
        $manaEditKyc = LaratrustPermission::firstOrCreate(
            [
                'name' => 'kyc_edit_rp',
                'display_name' => 'Manage View Kyc',
                'group' => 'User Kyc'
            ]
        );
        $manageDeleteKyc = LaratrustPermission::firstOrCreate(
            [
                'name' => 'kyc_delete_rp',
                'display_name' => 'Manage View Kyc',
                'group' => 'User Kyc'
            ]
        );

        $manageViewBlogs = LaratrustPermission::firstOrCreate(
            [
                'name' => 'blog_view_rp',
                'display_name' => 'Manage View Blogs',
                'group' => 'Blog'
            ]
        );
        $manageShowBlogs = LaratrustPermission::firstOrCreate(
            [
                'name' => 'blog_show_rp',
                'display_name' => 'Manage Show Blogs',
                'group' => 'Blog'
            ]
        );
        $manageCreateBlogs = LaratrustPermission::firstOrCreate(
            [
                'name' => 'blog_create_rp',
                'display_name' => 'Manage Create Blog',
                'group' => 'Blog'
            ]
        );
        $manageEditBlogs = LaratrustPermission::firstOrCreate(
            [
                'name' => 'blog_edit_rp',
                'display_name' => 'Manage Edit Blog',
                'group' => 'Blog'
            ]
        );
        $manageDeleteBlogs = LaratrustPermission::firstOrCreate(
            [
                'name' => 'blog_delete_rp',
                'display_name' => 'Manage Delete Blog',
                'group' => 'Blog'
            ]
        );
        $manageBulkBlogs = LaratrustPermission::firstOrCreate(
            [
                'name' => 'blog_bulk_rp',
                'display_name' => 'Manage Bulk Blog',
                'group' => 'Blog'
            ]
        );

        $manageViewCategoryType = LaratrustPermission::firstOrCreate(
            [
                'name' => 'category_type_view_rp',
                'display_name' => 'Manage View CategoryType',
                'group' => 'CategoryType'
            ]
        );
        $manageCreateCategoryType = LaratrustPermission::firstOrCreate(
            [
                'name' => 'category_type_create_rp',
                'display_name' => 'Manage Create CategoryType',
                'group' => 'CategoryType'
            ]
        );
        $manageEditCategoryType = LaratrustPermission::firstOrCreate(
            [
                'name' => 'category_type_edit_rp',
                'display_name' => 'Manage Edit CategoryType',
                'group' => 'CategoryType'
            ]
        );
        $manageDeleteCategoryType = LaratrustPermission::firstOrCreate(
            [
                'name' => 'category_type_delete_rp',
                'display_name' => 'Manage Delete CategoryType',
                'group' => 'CategoryType'
            ]
        );
        $manageBulkCategoryTypes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'category_type_bulk_rp',
                'display_name' => 'Manage Bulk Category Type',
                'group' => 'Category Type'
            ]
        );


        $manageViewCategories = LaratrustPermission::firstOrCreate(
            [
                'name' => 'category_view_rp',
                'display_name' => 'Manage View Categories',
                'group' => 'Category'
            ]
        );
        $manageCreateCategories = LaratrustPermission::firstOrCreate(
            [
                'name' => 'category_create_rp',
                'display_name' => 'Manage Create Category',
                'group' => 'Category'
            ]
        );
        $manageEditCategories = LaratrustPermission::firstOrCreate(
            [
                'name' => 'category_edit_rp',
                'display_name' => 'Manage Edit Category',
                'group' => 'Category'
            ]
        );
        $manageDeleteCategories = LaratrustPermission::firstOrCreate(
            [
                'name' => 'category_delete_rp',
                'display_name' => 'Manage Delete Category',
                'group' => 'Category'
            ]
        );
        $manageBulkCategories = LaratrustPermission::firstOrCreate(
            [
                'name' => 'category_bulk_rp',
                'display_name' => 'Manage Bulk Category',
                'group' => 'Category'
            ]
        );
        $manageViewSliderTypes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'slider_type_view_rp',
                'display_name' => 'Manage View Slider Type',
                'group' => 'SliderType'
            ]
        );
        $manageBulkSliderTypes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'slider_type_bulk_rp',
                'display_name' => 'Manage Bulk Slider Type',
                'group' => 'SliderType'
            ]
        );
        $manageCreateSliderTypes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'slider_type_create_rp',
                'display_name' => 'Manage Create Slider Type',
                'group' => 'Slider'
            ]
        );
        $manageEditSliderTypes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'slider_type_edit_rp',
                'display_name' => 'Manage Edit Slide Type',
                'group' => 'Slider'
            ]
        );
        $manageDeleteSliderTypes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'slider_type_delete_rp',
                'display_name' => 'Manage Delete Slider Type',
                'group' => 'Slider'
            ]
        );
        $manageViewSliders = LaratrustPermission::firstOrCreate(
            [
                'name' => 'slider_view_rp',
                'display_name' => 'Manage View Sliders',
                'group' => 'Slider'
            ]
        );
        $manageCreateSliders = LaratrustPermission::firstOrCreate(
            [
                'name' => 'slider_create_rp',
                'display_name' => 'Manage Create Slider',
                'group' => 'Slider'
            ]
        );
        $manageEditSliders = LaratrustPermission::firstOrCreate(
            [
                'name' => 'slider_edit_rp',
                'display_name' => 'Manage Edit Slider',
                'group' => 'Slider'
            ]
        );
        $manageDeleteSliders = LaratrustPermission::firstOrCreate(
            [
                'name' => 'slider_delete_rp',
                'display_name' => 'Manage Delete Slider',
                'group' => 'Slider'
            ]
        );
        $manageBulkSliders = LaratrustPermission::firstOrCreate(
            [
                'name' => 'slider_bulk_rp',
                'display_name' => 'Manage Bulk Slider',
                'group' => 'Slider'
            ]
        );
        $manageViewParagraphContents = LaratrustPermission::firstOrCreate(
            [
                'name' => 'paragraph_contents_view_rp',
                'display_name' => 'Manage View Paragraph Contents',
                'group' => 'Paragraph Content'
            ]
        );
        $manageCreateParagraphContents = LaratrustPermission::firstOrCreate(
            [
                'name' => 'paragraph_content_create_rp',
                'display_name' => 'Manage Create Paragraph Content',
                'group' => 'Paragraph Content'
            ]
        );
        $manageEditParagraphContents = LaratrustPermission::firstOrCreate(
            [
                'name' => 'paragraph_content_edit_rp',
                'display_name' => 'Manage Edit Paragraph Content',
                'group' => 'Paragraph Content'
            ]
        );
        $manageDeleteParagraphContents = LaratrustPermission::firstOrCreate(
            [
                'name' => 'paragraph_content_delete_rp',
                'display_name' => 'Manage Delete Paragraph Content',
                'group' => 'Paragraph Content'
            ]
        );
        $manageBulkParagraphContents = LaratrustPermission::firstOrCreate(
            [
                'name' => 'paragraph_content_bulk_rp',
                'display_name' => 'Manage Bulk Paragraph Content',
                'group' => 'Paragraph Content'
            ]
        );
        $manageViewPromoCodes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'promo_code_view_rp',
                'display_name' => 'Manage View Promo codes',
                'group' => 'Promo Code'
            ]
        );
        $manageAddPromoCodes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'promo_code_create_rp',
                'display_name' => 'Manage Add Promo codes',
                'group' => 'Promo Code'
            ]
        );
        $manageEditPromoCodes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'promo_code_edit_rp',
                'display_name' => 'Manage Edit Promo codes',
                'group' => 'Promo Code'
            ]
        );
        $manageDeletePromoCodes = LaratrustPermission::firstOrCreate(
            [
                'name' => 'promo_code_delete_rp',
                'display_name' => 'Manage Delete Promo codes',
                'group' => 'Promo Code'
            ]
        );
        $manageViewFaqs = LaratrustPermission::firstOrCreate(
            [
                'name' => 'faq_view_rp',
                'display_name' => 'Manage View Faqs',
                'group' => 'FAQs'
            ]
        );
        $manageCreateFaqs = LaratrustPermission::firstOrCreate(
            [
                'name' => 'faq_create_rp',
                'display_name' => 'Manage Create Faq',
                'group' => 'FAQs'
            ]
        );
        $manageEditFaqs = LaratrustPermission::firstOrCreate(
            [
                'name' => 'faq_edit_rp',
                'display_name' => 'Manage Edit Faq',
                'group' => 'FAQs'
            ]
        );
        $manageDeleteFaqs = LaratrustPermission::firstOrCreate(
            [
                'name' => 'faq_delete_rp',
                'display_name' => 'Manage Delete Faq',
                'group' => 'FAQs'
            ]
        );
        $manageBulkFaqs = LaratrustPermission::firstOrCreate(
            [
                'name' => 'faq_bulk_rp',
                'display_name' => 'Manage Delete Faq',
                'group' => 'FAQs'
            ]
        );
        $manageViewLocations = LaratrustPermission::firstOrCreate(
            [
                'name' => 'location_view_rp',
                'display_name' => 'Manage View Locations',
                'group' => 'Location'
            ]
        );
        $manageCreateLocations = LaratrustPermission::firstOrCreate(
            [
                'name' => 'location_create_rp',
                'display_name' => 'Manage Create Location',
                'group' => 'Location'
            ]
        );
        $manageEditLocations = LaratrustPermission::firstOrCreate(
            [
                'name' => 'location_edit_rp',
                'display_name' => 'Manage Edit Location',
                'group' => 'Location'
            ]
        );
        $manageDeleteLocations = LaratrustPermission::firstOrCreate(
            [
                'name' => 'location_delete_rp',
                'display_name' => 'Manage Delete Location',
                'group' => 'Location'
            ]
        );
        $manageViewSubscriptionPlans = LaratrustPermission::firstOrCreate(
            [
                'name' => 'subscription_plan_view_rp',
                'display_name' => 'Manage View Subscription Plans',
                'group' => 'Subscription Plan'
            ]
        );
        $manageCreateSubscriptionPlans = LaratrustPermission::firstOrCreate(
            [
                'name' => 'subscription_plan_create_rp',
                'display_name' => 'Manage Create Subscription Plan',
                'group' => 'Subscription Plan'
            ]
        );
        $manageEditSubscriptionPlans = LaratrustPermission::firstOrCreate(
            [
                'name' => 'subscription_plan_edit_rp',
                'display_name' => 'Manage Edit Subscription Plan',
                'group' => 'Subscription Plan'
            ]
        );
        $manageDeleteSubscriptionPlans = LaratrustPermission::firstOrCreate(
            [
                'name' => 'subscription_plan_delete_rp',
                'display_name' => 'Manage Delete Subscription Plan',
                'group' => 'Subscription Plan'
            ]
        );
        $manageBulkSubscriptionPlans = LaratrustPermission::firstOrCreate(
            [
                'name' => 'subscription_plan_bulk_rp',
                'display_name' => 'Manage Delete Subscription Plan',
                'group' => 'Subscription Plan'
            ]
        );
        $manageViewMailTemplates = LaratrustPermission::firstOrCreate(
            [
                'name' => 'mail_templates_view_rp',
                'display_name' => 'Manage View Mail Templates',
                'group' => 'Mail/SMS/Template'
            ]
        );
        $manageCreateMailTemplates = LaratrustPermission::firstOrCreate(
            [
                'name' => 'mail_templates_create_rp',
                'display_name' => 'Manage Create Mail Template',
                'group' => 'Mail/SMS/Template'
            ]
        );
        $manageEditMailTemplates = LaratrustPermission::firstOrCreate(
            [
                'name' => 'mail_templates_edit_rp',
                'display_name' => 'Manage Edit Mail Template',
                'group' => 'Mail/SMS/Template'
            ]
        );
        $manageDeleteMailTemplates = LaratrustPermission::firstOrCreate(
            [
                'name' => 'mail_templates_delete_rp',
                'display_name' => 'Manage Delete Mail Template',
                'group' => 'Mail/SMS/Template'
            ]
        );
        $manageBulkMailTemplates = LaratrustPermission::firstOrCreate(
            [
                'name' => 'mail_templates_bulk_rp',
                'display_name' => 'Manage Bulk Mail Template',
                'group' => 'Mail/SMS/Template'
            ]
        );
        $manageViewPages = LaratrustPermission::firstOrCreate(
            [
                'name' => 'page_view_rp',
                'display_name' => 'Manage View Pages',
                'group' => 'Page'
            ]
        );
        $manageCreatePages = LaratrustPermission::firstOrCreate(
            [
                'name' => 'page_create_rp',
                'display_name' => 'Manage Create Page',
                'group' => 'Page'
            ]
        );
        $manageEditPages = LaratrustPermission::firstOrCreate(
            [
                'name' => 'page_edit_rp',
                'display_name' => 'Manage Edit Page',
                'group' => 'Page'
            ]
        );
        $manageDeletePages = LaratrustPermission::firstOrCreate(
            [
                'name' => 'page_delete_rp',
                'display_name' => 'Manage Delete Page',
                'group' => 'Page'
            ]
        );
        $manageBulkPages = LaratrustPermission::firstOrCreate(
            [
                'name' => 'page_bulk_rp',
                'display_name' => 'Manage Bulk Page',
                'group' => 'Page'
            ]
        );
        $manageAccessGeneralSettings = LaratrustPermission::firstOrCreate(
            [
                'name' => 'general_setting_view_rp',
                'display_name' => 'Manage Access General Setting',
                'group' => 'Setting'
            ]
        );
        $manageAccessCurrencySettings = LaratrustPermission::firstOrCreate(
            [
                'name' => 'currency_setting_view_rp',
                'display_name' => 'Manage Access Currency Setting',
                'group' => 'Setting'
            ]
        );
        $manageAccessDateTimeSettings = LaratrustPermission::firstOrCreate(
            [
                'name' => 'date_time_setting_view_rp',
                'display_name' => 'Manage Access Date Time Setting',
                'group' => 'Setting'
            ]
        );
        $manageAccessNotificationSettings = LaratrustPermission::firstOrCreate(
            [
                'name' => 'notification_setting_view_rp',
                'display_name' => 'Manage Access Notification Setting',
                'group' => 'Setting'
            ]
        );
        $manageAccessSignatureSettings = LaratrustPermission::firstOrCreate(
            [
                'name' => 'signature_update_setting_view_rp',
                'display_name' => 'Manage Signature For Invoice Setting',
                'group' => 'Setting'
            ]
        );
        $manageAccessTroubleshootSettings = LaratrustPermission::firstOrCreate(
            [
                'name' => 'troubleshoot_setting_view_rp',
                'display_name' => 'Manage Access Troubleshoot Setting',
                'group' => 'Setting'
            ]
        );
        $manageAccessEmailSettings = LaratrustPermission::firstOrCreate(
            [
                'name' => 'email_setting_view_rp',
                'display_name' => 'Manage Access Email Setting',
                'group' => 'Setting'
            ]
        );
        $manageAccessSmsSettings = LaratrustPermission::firstOrCreate(
            [
                'name' => 'sms_setting_view_rp',
                'display_name' => 'Manage Access Sms Setting',
                'group' => 'Setting'
            ]
        );
        $manageAccessFcmSettings = LaratrustPermission::firstOrCreate(
            [
                'name' => 'fcm_setting_view_rp',
                'display_name' => 'Manage Access Fcm Setting',
                'group' => 'Setting'
            ]
        );
        $manageControlBasicDetails = LaratrustPermission::firstOrCreate(
            [
                'name' => 'control_basic_detail_view_rp',
                'display_name' => 'Manage Control Basic Details',
                'group' => 'Website Setup'
            ]
        );
        $manageControlSocialLoginsDetails = LaratrustPermission::firstOrCreate(
            [
                'name' => 'control_social_logins_detail_view_rp',
                'display_name' => 'Manage Control Social Logins Details',
                'group' => 'Website Setup'
            ]
        );
        $manageViewSeoTags = LaratrustPermission::firstOrCreate(
            [
                'name' => 'seo_tag_view_rp',
                'display_name' => 'Manage View seo Tags',
                'group' => 'SEO Tag'
            ]
        );
        $manageCreateSeoTags = LaratrustPermission::firstOrCreate(
            [
                'name' => 'seo_tag_create_rp',
                'display_name' => 'Manage Create SEO Tags',
                'group' => 'SEO Tag'
            ]
        );
        $manageEditSeoTags = LaratrustPermission::firstOrCreate(
            [
                'name' => 'seo_tag_edit_rp',
                'display_name' => 'Manage Edit SEO Tags',
                'group' => 'SEO Tag'
            ]
        );
        $manageDeleteSeoTags = LaratrustPermission::firstOrCreate(
            [
                'name' => 'seo_tag_delete_rp',
                'display_name' => 'Manage Delete SEO Tags',
                'group' => 'SEO Tag'
            ]
        );
        $manageBulkSeoTags = LaratrustPermission::firstOrCreate(
            [
                'name' => 'seo_tag_bulk_rp',
                'display_name' => 'Manage Bulk SEO Tags',
                'group' => 'SEO Tag'
            ]
        );

        $manageFeaturesActivation = LaratrustPermission::firstOrCreate(
            [
                'name' => 'features_activation_view_rp',
                'display_name' => 'Manage Features Activation',
                'group' => 'Features Activation'
            ]
        );
        $manageSetupConfiguration = LaratrustPermission::firstOrCreate(
            [
                'name' => 'manage_setup_configuration_view_rp',
                'display_name' => 'Manage Setup Configuration',
                'group' => 'Setup Configuration'
            ]
        );
        $manageConstantManagement = LaratrustPermission::firstOrCreate(
            [
                'name' => 'manage_constant_management_view_rp',
                'display_name' => 'Manage Constant Management',
                'group' => 'Constant Management'
            ]
        );
        $manageResources = LaratrustPermission::firstOrCreate(
            [
                'name' => 'manage_resource_view_rp',
                'display_name' => 'Manage Resources',
                'group' => 'Resources'
            ]
        );
        $manageAdministrator = LaratrustPermission::firstOrCreate(
            [
                'name' => 'manage_administrator_view_rp',
                'display_name' => 'Manage Administrator',
                'group' => 'Administrator'
            ]
        );
        $manageManage = LaratrustPermission::firstOrCreate(
            [
                'name' => 'manage_marketing_view_rp',
                'display_name' => 'Manage Marketing',
                'group' => 'Manage'
            ]
        );

        $manageBulkPromoCode = LaratrustPermission::firstOrCreate(
            [
                'name' => 'promo_code_bulk_rp',
                'display_name' => 'Manage Bulk Promo Code',
                'group' => 'Manage Promo Code'
            ]
        );

        $manageViewUserSubscriptionPlan = LaratrustPermission::firstOrCreate(
            [
                'name' => 'user_subscription_plan_view_rp',
                'display_name' => 'Manage View User Subscription Plan',
                'group' => 'User Subscription'
            ]
        );
        $manageCreateUserSubscriptionPlan = LaratrustPermission::firstOrCreate(
            [
                'name' => 'user_subscription_plan_create_rp',
                'display_name' => 'Manage Create User Subscription Plan',
                'group' => 'User Subscription'
            ]
        );
        $manageEditUserSubscriptionPlan = LaratrustPermission::firstOrCreate(
            [
                'name' => 'user_subscription_plan_edit_rp',
                'display_name' => 'Manage Edit User Subscription Plan',
                'group' => 'User Subscription'
            ]
        );
        $manageDeleteUserSubscriptionPlan = LaratrustPermission::firstOrCreate(
            [
                'name' => 'user_subscription_plan_delete_rp',
                'display_name' => 'Manage Delete User Subscription Plan',
                'group' => 'User Subscription'
            ]
        );
        $manageBulkUserSubscriptionPlan = LaratrustPermission::firstOrCreate(
            [
                'name' => 'user_subscription_plan_bulk_rp',
                'display_name' => 'Manage Delete User Subscription Plan',
                'group' => 'User Subscription'
            ]
        );
        $manageViewFileManager = LaratrustPermission::firstOrCreate(
            [
                'name' => 'file_manager_view_rp',
                'display_name' => 'Manage View File Manager',
                'group' => 'File Manager'
            ]
        );

        $manageCreateFeedback = LaratrustPermission::firstOrCreate(
            [
                'name' => 'feedback_create_rp',
                'display_name' => 'Manage Create Feedback',
                'group' => 'Manage Feedback'
            ]
        );
        $manageEditFeedback = LaratrustPermission::firstOrCreate(
            [
                'name' => 'feedback_edit_rp',
                'display_name' => 'Manage Create Feedback',
                'group' => 'Manage Feedback'
            ]
        );
        $manageDeleteFeedback = LaratrustPermission::firstOrCreate(
            [
                'name' => 'feedback_delete_rp',
                'display_name' => 'Manage Create Feedback',
                'group' => 'Manage Feedback'
            ]
        );
        $manageBulkFeedback = LaratrustPermission::firstOrCreate(
            [
                'name' => 'feedback_bulk_rp',
                'display_name' => 'Manage Bulk Feedback',
                'group' => 'Manage Feedback'
            ]
        );
        $manageViewNotification = LaratrustPermission::firstOrCreate(
            [
                'name' => 'notification_view_rp',
                'display_name' => 'Manage View Notification',
                'group' => 'Notification'
            ]
        );
        $manageEditNotification = LaratrustPermission::firstOrCreate(
            [
                'name' => 'notification_edit_rp',
                'display_name' => 'Manage Edit Notification',
                'group' => 'Notification'
            ]
        );
        $manageCreateDocument = LaratrustPermission::firstOrCreate(
            [
                'name' => 'document_create_rp',
                'display_name' => 'Manage Create Document',
                'group' => 'Document'
            ]
        );
        $manageViewBroadcast = LaratrustPermission::firstOrCreate(
            [
                'name' => 'broadcast_view_rp',
                'display_name' => 'Manage View Broadcast',
                'group' => 'Broadcast'
            ]
        );
        $manageViewBroadcast = LaratrustPermission::firstOrCreate(
            [
                'name' => 'debug_jobs_view_rp',
                'display_name' => 'Manage Debug Jobs',
                'group' => 'Debug Job'
            ]
        );
        $manageViewBroadcast = LaratrustPermission::firstOrCreate(
            [
                'name' => 'debug_jobs_delete_rp',
                'display_name' => 'Manage Debug Jobs',
                'group' => 'Debug Job'
            ]
        );

        // Attaching
        $admin->attachPermission($accessByAdmin);
        $admin->attachPermission($manageViewOrders);
        $admin->attachPermission($manageShowOrders);
        $admin->attachPermission($manageBulkOrders);
        $admin->attachPermission($manageCreateOrders);
        $admin->attachPermission($manageEditOrders);
        $admin->attachPermission($manageDeleteOrders);
        $admin->attachPermission($manageViewPayouts);
        $admin->attachPermission($manageShowPayouts);
        $admin->attachPermission($manageBulkPayouts);
        $admin->attachPermission($manageCreatePayouts);
        $admin->attachPermission($manageEditPayouts);
        $admin->attachPermission($manageDeletePayouts);
        $admin->attachPermission($manageViewItems);
        $admin->attachPermission($manageBulkItems);
        $admin->attachPermission($manageShowItems);
        $admin->attachPermission($manageCreateItems);
        $admin->attachPermission($manageEditItems);
        $admin->attachPermission($manageDeleteItems);
        $admin->attachPermission($manageFeedbackItems);
        $admin->attachPermission($manageViewUsers);
        $admin->attachPermission($manageCreateUsers);
        $admin->attachPermission($manageEditUsers);
        $admin->attachPermission($manageShowUsers);
        $admin->attachPermission($manageControlMfaUsers);
        $admin->attachPermission($manageDeleteUsers);
        $admin->attachPermission($manageBulkUsers);
        $admin->attachPermission($manageControlWallet);
        $admin->attachPermission($manageEditWallet);
        $admin->attachPermission($manageViewRoles);
        $admin->attachPermission($manageCreateRoles);
        $admin->attachPermission($manageEditRoles);
        $admin->attachPermission($manageDeleteRoles);
        $admin->attachPermission($manageViewPermissions);
        $admin->attachPermission($manageCreatePermissions);
        $admin->attachPermission($manageEditPermissions);
        $admin->attachPermission($manageDeletePermissions);
        $admin->attachPermission($manageViewEnquiries);
        $admin->attachPermission($manageShowEnquiries);
        $admin->attachPermission($manageBulkEnquiries);
        $admin->attachPermission($manageCreateEnquiries);
        $admin->attachPermission($manageEditEnquiries);
        $admin->attachPermission($manageDeleteEnquiries);
        $admin->attachPermission($manageViewTickets);
        $admin->attachPermission($manageShowTickets);
        $admin->attachPermission($manageMeetTickets);
        $admin->attachPermission($manageBulkTickets);
        $admin->attachPermission($manageCreateTickets);
        $admin->attachPermission($manageEditTickets);
        $admin->attachPermission($manageDeleteTickets);
        $admin->attachPermission($manageViewNewsletters);
        $admin->attachPermission($manageShowNewsletters);
        $admin->attachPermission($manageBulkNewsletters);
        $admin->attachPermission($manageCreateNewsletters);
        $admin->attachPermission($manageEditNewsletters);
        $admin->attachPermission($manageDeleteNewsletters);
        $admin->attachPermission($manageViewLeads);
        $admin->attachPermission($manageShowLeads);
        $admin->attachPermission($manageBulkLeads);
        $admin->attachPermission($manageCreateLeads);
        $admin->attachPermission($manageEditLeads);
        $admin->attachPermission($manageDeleteLeads);
        $admin->attachPermission($manageViewNotes);
        $admin->attachPermission($manageShowNotes);
        $admin->attachPermission($manageBulkNotes);
        $admin->attachPermission($manageCreateNotes);
        $admin->attachPermission($manageEditNotes);
        $admin->attachPermission($manageDeleteNotes);
        $admin->attachPermission($manageViewContacts);
        $admin->attachPermission($manageShowContacts);
        $admin->attachPermission($manageBulkContacts);
        $admin->attachPermission($manageCreateContacts);
        $admin->attachPermission($manageEditContacts);
        $admin->attachPermission($manageDeleteContacts);
        $admin->attachPermission($manageViewAddresses);
        $admin->attachPermission($manageShowAddresses);
        $admin->attachPermission($manageBulkAddresses);
        $admin->attachPermission($manageCreateAddresses);
        $admin->attachPermission($manageEditAddresses);
        $admin->attachPermission($manageDeleteAddresses);
        $admin->attachPermission($manageViewBanks);
        $admin->attachPermission($manageShowBanks);
        $admin->attachPermission($manageBulkBanks);
        $admin->attachPermission($manageCreateBanks);
        $admin->attachPermission($manageEditBanks);
        $admin->attachPermission($manageDeleteBanks);
        $admin->attachPermission($manageViewKyc);
        $admin->attachPermission($manageShowKyc);
        $admin->attachPermission($manaEditKyc);
        $admin->attachPermission($manageDeleteKyc);
        $admin->attachPermission($manageViewBlogs);
        $admin->attachPermission($manageShowBlogs);
        $admin->attachPermission($manageCreateBlogs);
        $admin->attachPermission($manageEditBlogs);
        $admin->attachPermission($manageDeleteBlogs);
        $admin->attachPermission($manageBulkBlogs);
        $admin->attachPermission($manageBulkCategoryTypes);
        $admin->attachPermission($manageViewCategoryType);
        $admin->attachPermission($manageCreateCategoryType);
        $admin->attachPermission($manageEditCategoryType);
        $admin->attachPermission($manageDeleteCategoryType);
        $admin->attachPermission($manageViewCategories);
        $admin->attachPermission($manageCreateCategories);
        $admin->attachPermission($manageEditCategories);
        $admin->attachPermission($manageDeleteCategories);
        $admin->attachPermission($manageBulkCategories);
        $admin->attachPermission($manageBulkSliderTypes);
        $admin->attachPermission($manageCreateSliderTypes);
        $admin->attachPermission($manageEditSliderTypes);
        $admin->attachPermission($manageViewSliderTypes);
        $admin->attachPermission($manageDeleteSliderTypes);
        $admin->attachPermission($manageViewSliders);
        $admin->attachPermission($manageCreateSliders);
        $admin->attachPermission($manageEditSliders);
        $admin->attachPermission($manageDeleteSliders);
        $admin->attachPermission($manageBulkSliders);
        $admin->attachPermission($manageViewParagraphContents);
        $admin->attachPermission($manageCreateParagraphContents);
        $admin->attachPermission($manageEditParagraphContents);
        $admin->attachPermission($manageDeleteParagraphContents);
        $admin->attachPermission($manageBulkParagraphContents);
        $admin->attachPermission($manageViewFaqs);
        $admin->attachPermission($manageCreateFaqs);
        $admin->attachPermission($manageEditFaqs);
        $admin->attachPermission($manageDeleteFaqs);
        $admin->attachPermission($manageBulkFaqs);
        $admin->attachPermission($manageViewLocations);
        $admin->attachPermission($manageCreateLocations);
        $admin->attachPermission($manageEditLocations);
        $admin->attachPermission($manageDeleteLocations);
        $admin->attachPermission($manageViewSubscriptionPlans);
        $admin->attachPermission($manageCreateSubscriptionPlans);
        $admin->attachPermission($manageEditSubscriptionPlans);
        $admin->attachPermission($manageDeleteSubscriptionPlans);
        $admin->attachPermission($manageBulkSubscriptionPlans);
        $admin->attachPermission($manageViewMailTemplates);
        $admin->attachPermission($manageCreateMailTemplates);
        $admin->attachPermission($manageEditMailTemplates);
        $admin->attachPermission($manageDeleteMailTemplates);
        $admin->attachPermission($manageBulkMailTemplates);
        $admin->attachPermission($manageViewPages);
        $admin->attachPermission($manageCreatePages);
        $admin->attachPermission($manageEditPages);
        $admin->attachPermission($manageDeletePages);
        $admin->attachPermission($manageBulkPages);
        $admin->attachPermission($manageAccessGeneralSettings);
        $admin->attachPermission($manageAccessCurrencySettings);
        $admin->attachPermission($manageAccessDateTimeSettings);
        $admin->attachPermission($manageAccessNotificationSettings);
        $admin->attachPermission($manageAccessTroubleshootSettings);
        $admin->attachPermission($manageAccessEmailSettings);
        $admin->attachPermission($manageAccessSmsSettings);
        $admin->attachPermission($manageAccessFcmSettings);
        $admin->attachPermission($manageControlBasicDetails);
        $admin->attachPermission($manageControlSocialLoginsDetails);
        $admin->attachPermission($manageViewSeoTags);
        $admin->attachPermission($manageCreateSeoTags);
        $admin->attachPermission($manageEditSeoTags);
        $admin->attachPermission($manageDeleteSeoTags);
        $admin->attachPermission($manageBulkSeoTags);
        $admin->attachPermission($manageFeaturesActivation);
        $admin->attachPermission($manageSetupConfiguration);
        $admin->attachPermission($manageConstantManagement);
        $admin->attachPermission($manageResources);
        $admin->attachPermission($manageAdministrator);
        $admin->attachPermission($manageManage);

        $admin->attachPermission($manageBulkPromoCode);
        $admin->attachPermission($manageViewUserSubscriptionPlan);
        $admin->attachPermission($manageCreateUserSubscriptionPlan);
        $admin->attachPermission($manageEditUserSubscriptionPlan);
        $admin->attachPermission($manageDeleteUserSubscriptionPlan);
        $admin->attachPermission($manageBulkUserSubscriptionPlan);
        $admin->attachPermission($manageViewFileManager);
        $admin->attachPermission($manageCreateFeedback);
        $admin->attachPermission($manageEditFeedback);
        $admin->attachPermission($manageDeleteFeedback);
        $admin->attachPermission($manageBulkFeedback);
        $admin->attachPermission($manageViewNotification);
        $admin->attachPermission($manageEditNotification);
        $admin->attachPermission($manageViewBroadcast);
        $admin->attachPermission($manageCreateDocument);
    }
}
