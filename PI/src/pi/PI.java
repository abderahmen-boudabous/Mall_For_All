/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package pi;

import java.util.ArrayList;
import moodels.User;
import service.UserService;
import utis.Maconnexion;

/**
 *
 * @author user
 */
public class PI {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        // TODO code application logic here
        
        
       //Maconnexion m = new Maconnexion ();
       
        User u = new User();
        UserService us = new UserService();
//        us.delete(76);
       
      ArrayList<User> listuser = new ArrayList<>();
listuser = us.readAll();
System.out.println(listuser);
      System.out.println(us.sortBy("nom", "ASC"));
//      System.out.println(us.readById(75));
//      
    }
    
}
