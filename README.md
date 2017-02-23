# a2
Assignment 2 Scrabble Calculator

Scrabble Calculator calculates the word score for any word both horizontally and vertically.
User Inputs:
    - word (no default)
    - selection of horizontal/vertical (default horizontal)
    - user specified position (x,y) (default (any,any))
    - calculate the bingo score for 7 letter words (default off)

The (x,y) position is 1-based, i.e (1,1) represents the top left tile and (15,15) represents the bottom right tile.   Internally, the position is 0-based (for array indexing), so there is some conversion going on between the front anc back ends.

The only required input is the word, which must be 2-7 characters.  There is no check to determine if the word is an acceptable scrabble word as per a scrabble dictionary, however, at least 1 vowel is required.
Errors are reported in the Score Summary for no word specified, min/max length violations, use of non-alpha characters and no vowel.

When the user clicks the calculate button, both the min and max scores for the word are calculated.  The position of the word for both these scores is  determined and reported in the Score Summary.  

In additon, if the user has not specified a position (i.e. (any, any)), a random position is selected and the word is layed out on the scrabble board at that position.  The score for this specific position is also calculated and reported.

The user has the option to select word orientation i.e horizontal or vertical positioning.  Scores, positions and layout are all determined based on the orientation selected.

If the user selects a position, the word is layed out at that postiion and the score is calculated based on that position.  Note the score and layout takes into account the orientation selected.

If the selected position does not allow the word to fit completely on the scrabble board, an error is shown in the Score Summary indicating that the word cannot fit.  The error message details the position and whether it can't fit the selected orientation.

Note that the user can select various combinations of position.  If the user selects only the x-position and leaves the y-position as 'any', a random y-position is generated.  The reverse is true for selecting only a specific x-position.  If the word cannot fit on the board based on any selection of position and orientation, an error is reported in the Score Summary.

If the bingo score checkbox is selected, the Score Summary reports the bingo score bonus only if the word is 7 characters.  Note that the bingo score is reported in addition to the non-bingo score.

Scrabble Calculator is best viewed in a browser window at 100%.  Firefox and IE appear to render the board the best.  I have noticed issues with Chrome in that the board is not rendered properly initially and then the scrabble tiles for the word do not line up properly.  I have been unable to resolve this issue.
