const input = document.getElementById("hardSkills");
const btnSubmit = document.getElementById("hardSkillSubmit");
const skillList = document.getElementById("skillList");
const list = document.getElementById("list");

const skill = input.value.trim()

class Skill {
  constructor(skill,) {
    this.skill = skill;
  };
  creationSkill() {
    localStorage.setItem('skill', JSON.stringify(skill));
    const skillSaved = JSON.parse(localStorage.getItem('skill'));
  };

input.addEventListener('submit', (clickSubmit) => {
  clickSubmit.preventDefault();
  newSkill = new Skill(skill);
});
