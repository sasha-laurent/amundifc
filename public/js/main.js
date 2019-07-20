/* 
 * Copyright (C) 2019 sasha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function switchTeam(element) {
    let switchUrl = $(element).attr('data-url');
    let playerRow = $(element).parent().parent();

    playerRow.fadeOut(300, function(){
        jQuery
            .ajax({
                url: switchUrl
            })
            .done(function(result){
                if (false !== result.newTeamId) {
                    playerRow.children().first().children().toggleClass('float-left');
                    playerRow.children().first().children().toggleClass('float-right');
                    playerRow.appendTo('tbody[data-team="'+result.newTeamId+'"]');
                }
            })
            .fail(function(result){
            })
            .always(function(result) {
                playerRow.fadeIn(300);
            });
    });
}

function removePlayer(element) {
    let removePlayerUrl = $(element).attr('data-url');
    let playerRow = $(element).parent().parent();

    playerRow.fadeOut(300, function(){
        jQuery
            .ajax({
                url: removePlayerUrl
            })
            .done(function(result){
                if (false !== result.removed) {
                    playerRow.remove();
                }
            })
            .fail(function(result){
                playerRow.fadeIn(300);
            });
    });
}

$(document).ready(function() {
    $('.js-datepicker').datepicker({
        weekStart: 1,
        daysOfWeekDisabled: "0,6",
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
});
