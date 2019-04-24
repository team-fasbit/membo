.ossn-wall-container .controls li:not(:last-child) {
    height: 40px;
    width: 40px;
    vertical-align: middle;
    text-align: center;
}
.ossn-wall-container .controls li:not(:last-child) i {
    margin-right: 0;
}
.ossn-photos li {
    width: 202px;
}
.ossn-group-profile .ossn-layout-module .module-contents .delete-group {
    margin-top: 5px;
    margin-bottom: 5px;
}
.emembers-page .ossn-users-list-item {
    margin-left: 0;
    margin-right: 0;
    padding-bottom: 0;
}

@media (max-width: 767px) {
    .ossn-group-profile .ossn-group-members .userimg {
        height: 75px;
    }
    .ossn-group-profile .ossn-group-members img,
    .ossn-search-page .ossn-users-list-item img,
    .ossn-profile .ossn-users-list-item img {
        max-width: 100%;
        height: auto;
        max-height: 100%;
    }
}

@media (max-width: 480px) {
    .emembers-page .ossn-users-list-item .uinfo a,
    .ossn-search-page .ossn-users-list-item,
    .ossn-profile .ossn-users-list-item {
        padding-bottom: 0;
    }
    .ossn-group-profile .ossn-group-members a.userlink,
    .emembers-page .ossn-users-list-item .uinfo a,
    .ossn-profile .ossn-users-list-item .uinfo a {
        line-height: 30px;
    }
    .emembers-page .ossn-users-list-item img,
    .ossn-search-page .ossn-users-list-item img,
    .ossn-profile .ossn-users-list-item img {
        margin-top: 10px;
    }
    .ossn-group-profile .ossn-group-members img {
        margin-top: 25px;
        width: 100%;
        height: auto;
    }
    .ossn-search-page .ossn-users-list-item .uinfo a {
        width: auto;
        line-height: 30px;
    }
    .ossn-videos-list .video-image {
        width: 100% !important;
    }
}