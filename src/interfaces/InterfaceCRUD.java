/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package interfaces;

import java.util.ArrayList;

/**
 *
 * @author user
 */
public interface InterfaceCRUD <T>
 {
    void insert(T t);
    void delete(int id);
    void update(T t);
    ArrayList<T> readAll();
    T readById(int id); 
    ArrayList<T> sortBy(String nom_column,String Asc_Dsc);
    
}
