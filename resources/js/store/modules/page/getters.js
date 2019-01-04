const myName = state => state.user.name;
const myPicture = state => state.user.picture;
const mySettings = state => state.user.settings;
const myEmail = state => state.user.email;
const myTeam = state => state.user.team;
const myRole = state => state.user.role_title;
const myPrograms = state => state.user.programs;
const myLocation = state => state.user.focus_location;
const myBu = state => state.user.focus_business_unit;
const myChannel = state => state.user.focus_channel;
const myExtension = state => state.user.extension;

const pageView = state => state.usersPage.view;
const pageSelected = state => state.usersPage.select;
const pageSearch = state => state.usersPage.search;
const users = state => state.users;
const form = state => state.userForm;
const profilePage = state => state.profilePage;

const filteredUsers = (state, getters) => {
	let users = getters.users;
	let query = getters.pageSearch;
	if (query != '') {
		users = _.filter(users, u => {
			if (u.name) {
				return u.name.toLowerCase().includes(query.toLowerCase());
			}
		});
	} else {
		users = _.filter(users, u => {
			return u.name != null;
		});
	}
}

export default {
	myName,
	myPicture,
	mySettings,
	myEmail,
	myTeam,
	myRole,
	myPrograms,
	myLocation,
	myBu,
	myChannel,
	myExtension,

	pageView,
	pageSelected,
	pageSearch,
	users,
	form,
	profilePage,
	filteredUsers
};