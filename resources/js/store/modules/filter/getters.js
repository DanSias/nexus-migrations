const subGroup = state => {
  let group = state.group.toLowerCase();
  let selected = state.selected;

  if (selected == '' || selected == null) {
      return state.group;
  } else if (group == 'location') {
      return 'channel';
  } else if (group == 'channel') {
      return 'initiative';
  } else if (group == 'program') {
        let channel = state.channel;
        if (channel.length > 0) {
            return 'initiative'
        }
        return 'channel';
  }
};


export default {
  subGroup,
};